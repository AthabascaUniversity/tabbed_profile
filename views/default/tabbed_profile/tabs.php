<?php

$page_owner = elgg_get_page_owner_entity();


$profiles = elgg_get_entities_from_metadata(array(
    'types' => array('object'),
    'subtypes' => array('tabbed_profile'),
    'owner_guids' => array($page_owner->getGUID()),
    'metadata_names' => array('order'),
    'order_by_metadata' => array('name' => 'order', 'direction' => 'ASC', 'as' => 'integer'),
    'limit' => 7
));

if ((!$profiles || !is_array($profiles))) {
  $profiles = array();
  $profiles[] = tabbed_profile_generate_default_profile($page_owner);
}

$tabs = array();

if ($profiles) {
  foreach ($profiles as $profile) {
    $text = $profile->title;
    if ($page_owner->canEdit()) {
      $text .= '<span class="elgg-icon elgg-icon-settings-alt tabbed-profile-edit"></span>';
    }

    $tabs[] = array(
      'text' => $text,
      'href' => $profile->getURL(),
      'selected' => ($profile->getURL() == current_page_url()),
      'link_class' => 'tabbed_profile',
      'rel' => $profile->getGUID()
    );
  }
}

if ($page_owner->canEdit() && count($profiles < 7)) {
  elgg_load_js('lightbox');
  elgg_load_css('lightbox');
  
  $tabs[] = array(
    'text' => '<span class="elgg-icon elgg-icon-round-plus"></span>',
      'href' => elgg_get_site_url() . 'ajax/view/tabbed_profile/edit?guid=' . $page_owner->getGUID(),
      'class' => 'tabbed-profile-add',
      'link_class' => 'elgg-lightbox',
      'selected' => false
  );
}

echo elgg_view('navigation/tabs', array('tabs' => $tabs));