<?php

namespace Drupal\content_verification\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\content_verification\Services\ContentVerificationHelper;

/**
 * Controller for the content verification report.
 */
class ContentVerificationController extends ControllerBase {

  /**
   * The content verification helper service.
   *
   * @var \Drupal\content_verification\Service\ContentVerificationHelper
   */
  protected $contentVerificationHelper;

  /**
   * Constructs the ContentVerificationController.
   *
   * @param \Drupal\content_verification\Service\ContentVerificationHelper $contentVerificationHelper
   *   The content verification helper service.
   */
  public function __construct(ContentVerificationHelper $contentVerificationHelper) {
    $this->contentVerificationHelper = $contentVerificationHelper;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('content_verification.helper')
    );
  }

  /**
   * Generates a report for content verification.
   */
  public function report() {
    // Table header for the report.
    $header = [
      ['data' => $this->t('Title')],
      ['data' => $this->t('Flesch-Kincaid Score')],
      ['data' => $this->t('Expected Score')],
      ['data' => $this->t('Keywords Status')],
      ['data' => $this->t('Status')],
    ];

    $rows = [];

    // Get all nodes of type article.
    $nids = \Drupal::entityQuery('node')
      ->condition('type', 'article')
      ->accessCheck(TRUE)
      ->execute();

    // Load the nodes.
    $nodes = Node::loadMultiple($nids);

    // Loop through each node to calculate the score and check keywords.
    foreach ($nodes as $node) {
      $body = $node->get('body')->value;
      $flesch_kincaid_score = $this->contentVerificationHelper->calculateFleschKincaid($body);

      // Get the configured Flesch-Kincaid scale.
      $config = \Drupal::config('content_verification.settings');
      $flesch_kincaid_scale = $config->get('flesch_kincaid_scale') ?? '60-70';

      // Set the minimum score based on the scale.
      switch ($flesch_kincaid_scale) {
        case '90-100':
          $min_score = 90;
          break;
        case '60-70':
          $min_score = 60;
          break;
        case '30-50':
          $min_score = 30;
          break;
        case '0-30':
          $min_score = 0;
          break;
        default:
          $min_score = 60;
      }

      // Check for missing keywords.
      $keywords = $node->get('field_tags')->value;
      $keyword_list = array_map('trim', explode(',', $keywords));
      $missing_keywords = [];
      foreach ($keyword_list as $keyword) {
        if (stripos($body, $keyword) === FALSE) {
          $missing_keywords[] = $keyword;
        }
      }

      // Determine keywords status.
      $keywords_status = empty($missing_keywords) 
        ? $this->t('All keywords present') 
        : $this->t('Missing keywords: @keywords', ['@keywords' => implode(', ', $missing_keywords)]);

      // Determine the readability status.
      $status = ($flesch_kincaid_score >= $min_score) 
        ? $this->t('Pass') 
        : $this->t('Fail');

      // Add a row to the report table.
      $rows[] = [
        'title' => $node->toLink(),
        'flesch_kincaid_score' => $flesch_kincaid_score,
        'expected_score' => $min_score,
        'keywords_status' => $keywords_status,
        'status' => $status,
      ];
    }

    // Return the table render array.
    return [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('No articles found.'),
    ];
  }

}