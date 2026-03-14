<?php
/**
 * SEO helpers and crawlability improvements.
 *
 * @package Fire
 */

/**
 * Normalize a string so it is safe to use in metadata.
 *
 * @param string $text Raw text.
 * @return string
 */
function fire_seo_clean_text($text) {
  $text = wp_strip_all_tags((string) $text, true);
  $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
  $text = preg_replace('/\s+/', ' ', $text);

  return trim((string) $text);
}

/**
 * Recursively collect human-readable strings from ACF field data.
 *
 * @param mixed  $value Current field value.
 * @param array  $fragments Collected text fragments.
 * @param string $key Current array key.
 * @return void
 */
function fire_seo_collect_text_fragments($value, array &$fragments, $key = '') {
  $ignored_keys = array(
    'acf_fc_layout',
    'target',
    'url',
    'sizes',
    'mime_type',
    'link',
    'script',
    'location',
  );

  if (in_array($key, $ignored_keys, true)) {
    return;
  }

  if (is_array($value)) {
    foreach ($value as $child_key => $child_value) {
      fire_seo_collect_text_fragments($child_value, $fragments, is_string($child_key) ? $child_key : '');
    }

    return;
  }

  if (is_object($value)) {
    fire_seo_collect_text_fragments((array) $value, $fragments, $key);
    return;
  }

  if (!is_string($value)) {
    return;
  }

  $text = fire_seo_clean_text($value);

  if (empty($text)) {
    return;
  }

  if (filter_var($text, FILTER_VALIDATE_URL)) {
    return;
  }

  if (is_numeric($text)) {
    return;
  }

  if (mb_strlen($text) < 3) {
    return;
  }

  $fragments[] = $text;
}

/**
 * Build a fallback description for a post.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function fire_seo_generate_description($post_id) {
  if (empty($post_id)) {
    return '';
  }

  $post = get_post($post_id);

  if (!$post instanceof WP_Post) {
    return '';
  }

  $fragments = array();

  if ('animal' === $post->post_type) {
    $species = fire_seo_clean_text(get_field('species', $post_id));

    if (!empty($species)) {
      $fragments[] = sprintf(
        'Meet %1$s, a %2$s featured in Outback Ray Animal Shows.',
        $post->post_title,
        $species
      );
    } else {
      $fragments[] = sprintf(
        'Meet %s from Outback Ray Animal Shows.',
        $post->post_title
      );
    }
  }

  if (has_excerpt($post)) {
    $fragments[] = fire_seo_clean_text(get_the_excerpt($post));
  }

  if (!empty($post->post_content)) {
    $fragments[] = fire_seo_clean_text(strip_shortcodes($post->post_content));
  }

  if (function_exists('get_fields')) {
    $fields = get_fields($post_id);

    if (is_array($fields) && !empty($fields)) {
      fire_seo_collect_text_fragments($fields, $fragments);
    }
  }

  $fragments = array_values(array_unique(array_filter($fragments)));

  if (empty($fragments)) {
    return '';
  }

  $description = fire_seo_clean_text(implode(' ', $fragments));

  if (empty($description)) {
    return '';
  }

  if (function_exists('mb_strimwidth')) {
    return rtrim(mb_strimwidth($description, 0, 157, '...', 'UTF-8'));
  }

  return rtrim(substr($description, 0, 157), '. ') . '...';
}

/**
 * Supply a fallback meta description when Yoast does not have one.
 *
 * @param string $description Current description.
 * @return string
 */
function fire_seo_filter_metadesc($description) {
  if (!is_singular() && !is_front_page()) {
    return $description;
  }

  if (!empty(fire_seo_clean_text($description))) {
    return $description;
  }

  $generated_description = fire_seo_generate_description(get_queried_object_id());

  return !empty($generated_description) ? $generated_description : $description;
}
add_filter('wpseo_metadesc', 'fire_seo_filter_metadesc');
add_filter('wpseo_opengraph_desc', 'fire_seo_filter_metadesc');
add_filter('wpseo_twitter_description', 'fire_seo_filter_metadesc');

/**
 * Improve the home page title when it has been reduced to the brand name only.
 *
 * @param string $title Current title.
 * @return string
 */
function fire_seo_filter_front_page_title($title) {
  if (!is_front_page()) {
    return $title;
  }

  $site_name = fire_seo_clean_text(get_bloginfo('name'));
  $site_description = fire_seo_clean_text(get_bloginfo('description'));
  $normalized_title = fire_seo_clean_text($title);

  if (empty($site_name) || empty($site_description)) {
    return $title;
  }

  if ($normalized_title !== $site_name) {
    return $title;
  }

  return sprintf('%1$s | %2$s', $site_name, $site_description);
}
add_filter('wpseo_title', 'fire_seo_filter_front_page_title');

/**
 * Provide a stronger fallback document title if Yoast is disabled.
 *
 * @param array $title_parts Current document title parts.
 * @return array
 */
function fire_seo_filter_document_title_parts($title_parts) {
  if (!is_front_page()) {
    return $title_parts;
  }

  $site_description = fire_seo_clean_text(get_bloginfo('description'));

  if (empty($site_description)) {
    return $title_parts;
  }

  $title_parts['tagline'] = $site_description;

  return $title_parts;
}
add_filter('document_title_parts', 'fire_seo_filter_document_title_parts');

/**
 * Strip any Crawl-delay directive from the dynamic robots.txt output.
 *
 * A malformed Crawl-delay: 10 line was injected before the User-agent
 * directive, throttling search engine crawling and potentially causing
 * parsing issues for all crawlers.
 *
 * @param string $output The robots.txt content.
 * @return string
 */
add_filter('robots_txt', function ($output) {
  return preg_replace('/^Crawl-delay:.*\n?/mi', '', $output);
}, 999);

/**
 * Output LocalBusiness JSON-LD structured data on the front page.
 *
 * Tells Google this is a local entertainment business serving Northeast Ohio.
 */
function fire_seo_local_business_schema() {
  if (!is_front_page()) {
    return;
  }

  $schema = array(
    '@context'    => 'https://schema.org',
    '@type'       => 'EntertainmentBusiness',
    'name'        => 'Outback Ray\'s Amazing Animal Show',
    'url'         => home_url('/'),
    'telephone'   => '(216) 381-1130',
    'description' => 'Live animal shows for schools, birthday parties, libraries, senior centers, and community events across Northeast Ohio.',
    'image'       => get_template_directory_uri() . '/dist/assets/media/logo.png',
    'priceRange'  => '$$',
    'areaServed'  => array(
      array(
        '@type' => 'GeoCircle',
        'geoMidpoint' => array(
          '@type'     => 'GeoCoordinates',
          'latitude'  => 41.1684,
          'longitude' => -81.6401,
        ),
        'geoRadius' => '96560',
      ),
      array(
        '@type' => 'City',
        'name'  => 'Akron, OH',
      ),
      array(
        '@type' => 'City',
        'name'  => 'Cleveland, OH',
      ),
      array(
        '@type' => 'City',
        'name'  => 'Canton, OH',
      ),
      array(
        '@type' => 'City',
        'name'  => 'Youngstown, OH',
      ),
      array(
        '@type' => 'City',
        'name'  => 'Medina, OH',
      ),
      array(
        '@type' => 'City',
        'name'  => 'Wooster, OH',
      ),
      array(
        '@type' => 'City',
        'name'  => 'Cuyahoga Falls, OH',
      ),
      array(
        '@type' => 'City',
        'name'  => 'Kent, OH',
      ),
      array(
        '@type' => 'City',
        'name'  => 'Hudson, OH',
      ),
      array(
        '@type' => 'City',
        'name'  => 'Stow, OH',
      ),
      array(
        '@type' => 'City',
        'name'  => 'Mentor, OH',
      ),
      array(
        '@type' => 'City',
        'name'  => 'Elyria, OH',
      ),
      array(
        '@type' => 'City',
        'name'  => 'Lorain, OH',
      ),
      array(
        '@type' => 'City',
        'name'  => 'Mansfield, OH',
      ),
      array(
        '@type' => 'City',
        'name'  => 'Warren, OH',
      ),
      array(
        '@type' => 'City',
        'name'  => 'Parma, OH',
      ),
      array(
        '@type' => 'City',
        'name'  => 'Lakewood, OH',
      ),
      array(
        '@type' => 'City',
        'name'  => 'Strongsville, OH',
      ),
      array(
        '@type' => 'City',
        'name'  => 'Brunswick, OH',
      ),
      array(
        '@type' => 'City',
        'name'  => 'Barberton, OH',
      ),
      array(
        '@type' => 'City',
        'name'  => 'Wadsworth, OH',
      ),
      array(
        '@type' => 'City',
        'name'  => 'Massillon, OH',
      ),
      array(
        '@type' => 'City',
        'name'  => 'Sandusky, OH',
      ),
    ),
    'aggregateRating' => array(
      '@type'       => 'AggregateRating',
      'ratingValue' => '5.0',
      'reviewCount' => '215',
    ),
    'sameAs' => array(
      'https://www.google.com/maps/place/Outback+Ray%27s+Amazing+Animal+Show/',
    ),
  );

  echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
}
add_action('wp_head', 'fire_seo_local_business_schema');

/**
 * Output FAQPage JSON-LD structured data on the FAQs page.
 *
 * Enables rich FAQ snippets in Google search results.
 */
function fire_seo_faq_schema() {
  if (!is_page('faqs')) {
    return;
  }

  $faqs = array(
    array(
      'q' => 'Can the show be done inside my home?',
      'a' => 'Yes! Outback Ray has been doing shows in people\'s homes for decades! The first floor is preferred as there are multiple trips to load &amp; unload with the animal carriers &amp; stairs can be difficult.',
    ),
    array(
      'q' => 'How much space is needed?',
      'a' => 'Outback Ray needs approximately a 5X8 area for him and the animals. The carriers are stackable making it easy to fit into most rooms.',
    ),
    array(
      'q' => 'When does the show have to be indoors?',
      'a' => 'Shows must be indoors when temperatures fall below 60 degrees, above 85 degrees (air-conditioned space preferred), or during inclement weather like rain or high winds.',
    ),
    array(
      'q' => 'When can shows be outdoors?',
      'a' => 'When temperatures are above a nice 60 degrees or better, outdoor shows are great! Shade needs to be provided for the animals.',
    ),
    array(
      'q' => 'What age group is best?',
      'a' => 'Outback Ray\'s Amazing Animal Show is perfect for any age group, from toddlers to seniors.',
    ),
    array(
      'q' => 'How much does the show cost?',
      'a' => 'Cost varies based on location, time of year, and event type. Contact Outback Ray for a personalized quote.',
    ),
    array(
      'q' => 'Is Outback Ray Licensed and Insured?',
      'a' => 'Yes! Outback Ray is fully licensed and insured, including USDA &amp; Board of Health.',
    ),
    array(
      'q' => 'Can people touch the animals?',
      'a' => 'Yes! Outback Ray\'s Amazing Animal Show is very hands-on. All of Ray\'s animals are captive born and raised by him.',
    ),
    array(
      'q' => 'Is there a limit to the amount of people who can attend?',
      'a' => 'No, there is not a limit to the amount of attendees. Outback Ray has performed for audiences of one as well as groups of a thousand+.',
    ),
    array(
      'q' => 'How far in advance do I need to schedule?',
      'a' => 'Summer months book faster so it\'s best to plan ahead. Contact Outback Ray to check availability for your date.',
    ),
    array(
      'q' => 'What areas do you serve?',
      'a' => 'Outback Ray serves Northeast Ohio &amp; surrounding areas including Akron, Cleveland, Canton, Youngstown, Medina, and more. Contact Ray to see if he travels to your area.',
    ),
    array(
      'q' => 'Can I request certain animals?',
      'a' => 'Requests are possible, but factors like feeding and shedding schedules apply. With live animals there are no guarantees, but Ray does his best to accommodate requests.',
    ),
    array(
      'q' => 'How long is the show?',
      'a' => 'Outback Ray\'s Amazing Animal Show averages 45 minutes to an hour, but can easily be extended.',
    ),
  );

  $main_entity = array();
  foreach ($faqs as $faq) {
    $main_entity[] = array(
      '@type' => 'Question',
      'name'  => $faq['q'],
      'acceptedAnswer' => array(
        '@type' => 'Answer',
        'text'  => $faq['a'],
      ),
    );
  }

  $schema = array(
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    'mainEntity' => $main_entity,
  );

  echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
}
add_action('wp_head', 'fire_seo_faq_schema');
