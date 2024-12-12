# API Service for Managing Articles and Tags

## Task
Implement an API service that will store articles with the ability to associate tags. Each article contains an identifier (`id`) and a title (`title`), and each tag consists of an identifier (`id`) and a name (`name`).

## API Functionality
The API service should provide the following capabilities:

1. Creating and editing tags. The request should return the created or updated tag.
2. Creating and editing articles with the ability to assign tags. The request should return the created or updated article.
3. Deleting articles without using a soft-delete mechanism.
4. Retrieving a full list of articles with the ability to filter by tags. The filter can specify multiple tags, and it should display articles that have all the specified tags (including outputting all tags).
5. Retrieving an article by `ID` with all associated tags.

## Requirements
You can implement it using any framework or pure PHP. The format of the input and output data should be JSON. Authorization is not required.

## Implementation
The service is implemented using the Lumen framework.

### Technology Stack
- Docker + Docker Compose
- PHP 8.3 FPM
- MySQL 8.0
- Nginx 1.17
- Lumen framework

## Installation and Setup Instructions

1. Clone the repository:
    ```bash
    git clone https://github.com/Simtel/rest-article-service
    ```

2. Build the containers:
    ```bash
    make build
    ```

3. Start the containers:
    ```bash
    make up
    ```

4. Copy the environment file:
    ```bash
    make env
    ```

5. Install dependencies using Composer:
    ```bash
    make composer-install
    ```

   **The previous steps can be replaced with the command:**
    ```bash
    make install
    ```

6. Run the migrations:
    ```bash
    make migrate
    ```

7. Seed the database with initial data:
    ```bash
    make db-seed
    ```

8. Run the tests:
    ```bash
    make test
    ```

## Conclusion
This API service will efficiently manage articles and tags, providing a simple interface for creating, updating, retrieving, and deleting records as needed.
