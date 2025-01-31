<?php
  /* Template Name: Style Guide */
  /**
   * The template for displaying the Style Guide
   *
   * @package Fire
   */

  get_header();
?>
	<main id="primary" class="site-main">
    <div class="container mx-auto mt-10 mb-20">
      <div>
        <div class="flex">
          <div class="flex items-center justify-center w-32 h-20 mb-4 mr-4 text-black bg-green">green</div>
          <div class="flex items-center justify-center w-32 h-20 mb-4 mr-4 text-black bg-light-green">light-green</div>
          <div class="flex items-center justify-center w-32 h-20 mb-4 mr-4 text-black bg-orange">orange</div>
          <div class="flex items-center justify-center w-32 h-20 mb-4 mr-4 text-black bg-light-orange">light-orange</div>
          <div class="flex items-center justify-center w-32 h-20 mb-4 mr-4 text-black bg-tan">tan</div>
          <div class="flex items-center justify-center w-32 h-20 mb-4 mr-4 text-black bg-light-tan">light-tan</div>
          <div class="flex items-center justify-center w-32 h-20 mb-4 mr-4 text-black bg-navy">navy</div>
          <div class="flex items-center justify-center w-32 h-20 mb-4 mr-4 text-black bg-light-navy">light-navy</div>
          <div class="flex items-center justify-center w-32 h-20 mb-4 mr-4 text-black bg-purple">purple</div>
          <div class="flex items-center justify-center w-32 h-20 mb-4 mr-4 text-black bg-light-purple">light-purple</div>
        </div>
      </div>

      <div class="flex flex-col gap-2">
        <div class="flex items-center justify-center w-full h-24 text-black bg-texture-waves">texture-waves</div>
        <div class="flex items-center justify-center w-full h-24 text-black bg-texture-croc">texture-croc</div>
        <div class="flex items-center justify-center w-full h-24 text-black bg-texture-snake">texture-snake</div>
        <div class="flex items-center justify-center w-full h-24 text-black bg-texture-leaves">texture-leaves</div>
        <div class="flex items-center justify-center w-full h-24 text-black bg-texture-leaves-2">texture-leaves-2</div>
      </div>
      <hr>

      <h1>h1: Lorem ipsum dolor sit amet consectetur adipisicing elit.</h1>
      <h2>h2: Lorem ipsum dolor sit amet consectetur adipisicing elit.</h2>
      <h3>h3: Lorem ipsum dolor sit amet consectetur adipisicing elit.</h3>
      <h4>h4: Lorem ipsum dolor sit amet consectetur adipisicing elit.</h4>
      <h5>h5: Lorem ipsum dolor sit amet consectetur adipisicing elit.</h5>
      <h6>h6: Lorem ipsum dolor sit amet consectetur adipisicing elit.</h6>

      <h1 class="heading-1">heading-1: Lorem ipsum dolor sit amet</h1>
      <h1 class="heading-2">heading-2: Lorem ipsum dolor sit amet</h1>
      <h1 class="heading-3">heading-3: Lorem ipsum dolor sit amet</h1>
      <h1 class="heading-4">heading-4: Lorem ipsum dolor sit amet</h1>
      <h1 class="heading-5">heading-5: Lorem ipsum dolor sit amet</h1>
      <h1 class="heading-6">heading-6: Lorem ipsum dolor sit amet</h1>

      <hr>

      <p class="text-lg">text-lg paragraph: Lorem ipsum dolor sit amet consectetur, adipisicing elit. Reprehenderit, voluptatibus perferendis recusandae error unde repudiandae. Iste ab eius quibusdam inventore?</p>

      <p>paragraph: Lorem ipsum dolor sit amet consectetur, adipisicing elit. Reprehenderit, voluptatibus perferendis recusandae error unde repudiandae. Iste ab eius quibusdam inventore?</p>

      <hr>

      <div class="flex flex-wrap">
        <button type="button" class="button button-primary">primary</button>
      </div>

      <hr>

      <div class="flex flex-wrap">
        <button type="button" class="mr-3 button button-transparent">button-transparent</button>
      </div>

      <hr>

      <div class="flex">
        <a href="#" class="link link-primary">primary</a>
      </div>

      <hr>

      <div class="w-1/2">
        <div class="flex">
          <div class="flex-grow mr-3 form-group">
            <label class="form-input-label">First Name</label>
            <input type="text" class="form-input">
          </div>
          <div class="flex-grow form-group">
            <label class="form-input-label">Last Name</label>
            <input type="text" class="form-input">
          </div>
        </div>
        <div class="form-group">
          <label class="form-input-label">Email Address</label>
          <input type="text" class="form-input">
        </div>
        <div class="form-group">
          <label class="form-input-label">Comment</label>
          <textarea type="text" class="form-input"></textarea>
        </div>
      </div>
    </div>
  </main>

<?php
  get_footer();
?>