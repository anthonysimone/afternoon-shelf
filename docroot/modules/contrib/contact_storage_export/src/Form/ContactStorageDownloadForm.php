<?php

namespace Drupal\contact_storage_export\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Core\Url;

/**
 * Class ContactStorageDownloadForm.
 *
 * @package Drupal\contact_storage_export\Form
 */
class ContactStorageDownloadForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'contact_storage_download_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $contact_form = '', $key = 0) {

    $form['contact_form'] = [
      '#type' => 'hidden',
      '#value' => $contact_form,
    ];
    $form['key'] = [
      '#type' => 'hidden',
      '#value' => $key,
    ];

    if ($contact_form) {

      $form['intro'] = [
        '#type' => 'item',
        '#plain_text' => $this->t('Your export is ready for download.'),
      ];

      $form['download_container'] = [
        '#type' => 'container',
      ];
      $form['download_container']['download'] = [
        '#type' => 'submit',
        '#value' => $this->t('Download'),
        '#attributes' => [
          'class' => [
            'button',
            'button--primary',
          ],
        ],
      ];

      $form['return'] = [
        '#title' => $this->t('Return to the export page.'),
        '#type' => 'link',
        '#url' => Url::fromRoute('entity.contact_form.export_form'),
      ];

    }
    else {

      $message = $this->t('An unknown error occurred preparing your download.');
      drupal_set_message($message, 'warning', FALSE);

    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $values = $form_state->getValues();

    // Get data from tempstore.
    $tempstore = \Drupal::service('user.private_tempstore')
      ->get('contact_storage_export');
    $data = $tempstore->get('data');

    if (isset($data[$values['key']])) {

      $export = $data[$values['key']];
      $headers = array(
        'Content-Type' => 'text/csv',
        'Content-Transfer-Encoding' => 'binary',
        'Pragma' => 'no-cache',
        'Expires' => '0',
        'Content-Disposition' => 'attachment; filename="' . $export['filename'] . '"',
      );

      // Send the export file.
      $response = new Response($export['csv_string'], 200, $headers);
      $response->send();
      exit();

    }
    else {
      $message = $this->t('Failed to download the file.');
      drupal_set_message($message, 'warning');
    }

  }

}
