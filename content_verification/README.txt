#### Introduction

This module provides functionality to verify and improve the readability and 
keyword usage in articles. 

It helps content creators meet specific criteria for word count, readability, 
and ensures that important keywords are included in the content.


#### Features

- Word Count Validation: Ensures that articles have a minimum number of words.
- Readability Check: Calculates the Flesch-Kincaid readability score and validates 
it based on configurable levels (e.g., easy, medium, difficult).
- Keyword Verification: Confirms that the defined tags (field_tags) are present 
in the body of the content.

#### Instalation

1.	Download and place the content_verification module in your modules/custom directory.
2.	Enable the module through the Drupal admin interface or using Drush:

    drush en content_verification

#### Configuration

1.	Navigate to Configuration > Content Authoring > Content Verification Settings.
2.	Set the minimum word count and select the Flesch-Kincaid readability scale (e.g., 60-70 for general audiences, 90-100 for children).

#### How it works

- When an editor creates or edits an article, the module checks the word count, readability score, and keyword presence.

- If the article does not meet the required standards, the editor will see an error message, indicating what needs to be improved (e.g., “The content readability score is too low”).

#### Version Compatibility
| Content Verification version | Drupal core | PHP  | Drush |
|------------------------------|-------------|------|-------|
| 1.0+                         | 10          | 8.1+ | 12+   |

#### Maintainers

See https://www.drupal.org/u/gretelg
