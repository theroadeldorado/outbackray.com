<?php
/* Set Responsive Pics variables (match TW config) */
if (class_exists('ResponsivePics')) {
  ResponsivePics::setBreakPoints([
    'sm'    => 0,
    'md'    => 768,
    'lg'    => 992,
    'xl'    => 1400,
    'xxl'   => 1800,
  ]);
  ResponsivePics::setImageQuality(100);
}