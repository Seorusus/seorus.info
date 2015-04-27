<?php
/**
 * @file
 * Definition of views_bootstrap_plugin_style.
 */

/**
 * Class to define a style plugin handler.
 */
class ViewsBootstrapGridPluginStyle extends views_plugin_style {
  /**
   * Definition.
   */
  public function option_definition() {
    $options = parent::option_definition();
    $options['alignment'] = array('default' => 'horizontal');
    $options['columns'] = array('default' => 2);
    $options['title_field'] = array('default' => NULL);
    return $options;
  }

  /**
   * Form.
   */
  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);

    if (isset($form['grouping'])) {
      $options = array();
      foreach (element_children($form['grouping']) as $key => $value) {
        if (!empty($form['grouping'][$key]['field']['#options']) && is_array($form['grouping'][$key]['field']['#options'])) {
          $options = array_merge($options, $form['grouping'][$key]['field']['#options']);
        }
      }

      // Delete grouping.
      // unset($form['grouping']);

      $form['title_field'] = array(
        '#type' => 'select',
        '#title' => t('Title field'),
        '#options' => $options,
        '#required' => TRUE,
        '#default_value' => $this->options['title_field'],
        '#description' => t('Select the field that will be used as the title.'),
      );
    }


    $form['alignment'] = array(
      '#type' => 'radios',
      '#title' => t('Alignment'),
      '#options' => array(
        'horizontal' => t('Horizontal'),
        'vertical' => t('Vertical'),
      ),
      '#description' => t('Horizontal alignment will place items starting in the upper left and moving right.
      Vertical alignment will place items starting in the upper left and moving down.'),
      '#default_value' => $this->options['alignment'],
    );

    $options = array(1, 2, 3, 4, 6, 12);

    $form['columns'] = array(
      '#type' => 'select',
      '#title' => t('Number of columns'),
      '#options' => array_combine($options, $options),
      '#required' => TRUE,
      '#default_value' => $this->options['columns'],
    );
  }
}
