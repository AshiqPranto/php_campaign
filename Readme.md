



## Setup Instructions

### Prerequisites

1. **Install PHP**
2. **Install MongoDB**
3. **Install MySQL**

### Installation Steps

1. **Install MongoDB PHP Extensions**
    ```sh
    pecl install mongodb
    ```
    Ensure to add `extension=mongodb` in your `php.ini` file.

2. **Install MongoDB PHP Library via Composer**
    ```sh
    composer require mongodb/mongodb
    ```

3. **Install MySQL Server and Create a User**
    Follow MySQL server installation instructions for your operating system. After installation, create a user and grant necessary privileges.

## Running the Script

1. **Place the Script in the Web Server's Root Directory**
    Ensure the PHP script is in the document root directory of your web server.

2. **Start Your Web Server**
    Make sure your web server is running.

3. **Send a POST Request with JSON Payload**
    Use a tool like Postman to send a POST request to the script's URL. 

    - **URL**: `http://localhost/index.php`
    - **Headers**: 
        - `Content-Type: application/json`
    - **JSON Payload**:
    ```json
    {
        "bidder_request": true,
        "campaigns": [
            {
                "campaign_name": "Test Campaign",
                "campaign_goal": 10000,
                "campaign_starts": "2024-06-01",
                "campaign_ends": "2024-06-30",
                "campaign_type": "CPM"
            },
            {
                "campaign_name": "Test Campaign 2",
                "campaign_goal": 20000,
                "campaign_starts": "2024-07-01",
                "campaign_ends": "2024-07-30",
                "campaign_type": "CPI"
            }
        ]
    }
    ```

## Database Schemas

### MongoDB

- **Database Name**: `adplaytechnology`
- **Collection Name**: `campaigns`
- **Document Structure**:
    ```json
    {
        "bidder_request": true,
        "campaigns": [
            {
                "campaign_name": "Test Campaign",
                "campaign_goal": 10000,
                "campaign_starts": "2024-06-01",
                "campaign_ends": "2024-06-30",
                "campaign_type": "CPM"
            },
            {
                "campaign_name": "Test Campaign 2",
                "campaign_goal": 20000,
                "campaign_starts": "2024-07-01",
                "campaign_ends": "2024-07-30",
                "campaign_type": "CPI"
            }
        ]
    }
    ```

### MySQL

- **Database Name**: `adplaytechnology`

#### `campaigns` Table
```sql
CREATE TABLE campaigns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    goal INT NOT NULL,
    starts DATE NOT NULL,
    ends DATE NOT NULL,
    campaign_type_id INT NOT NULL,
    FOREIGN KEY (campaign_type_id) REFERENCES campaign_types(id)
);
```

#### `campaign_types` Table
```sql
CREATE TABLE campaign_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_name VARCHAR(255) NOT NULL
);
```