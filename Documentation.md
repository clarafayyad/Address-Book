#API Endpoints Documentation

### 1.GETContacts.php

* This endpoint is used to fetch contacts from the database. 
* Path: http://localhost/addressbook/API/GETContacts.php


* HTTP method(s):

  * GET


* Request data:

  | Parameter   | Value                                    | Description                              | Required/Optional |
  | ----------- | :--------------------------------------- | ---------------------------------------- | ----------------- |
  | searchQuery | String.   <u>Ex:</u> 'John'.             | The search query.                        | Required          |
  | type        | String. Could be any of the following: 'all', 'phoneNumber', firstName', 'lastName', 'job', 'address', 'email'. | The search type. For example, the user can search for a contact using his/her first name. In this case, the type is firstName. | Required          |
  | pageNumber  | Number.                                  | The page number.                         | Required          |
  | isSearching | String.  Could be either 'true' or 'false'. | Whether the user is searching for a specific contact ('true') or just wants all contacts ('false') | Required          |

  ​

  * Example:

    * CURL:

      curl --request GET \
        --url 'http://localhost/addressbook/API/GETContacts.php?searchQuery=fayyad&pageNumber=1&isSearching=true&type=lastName'

    * Response:

  ```json
  { 
  "data": [
      {
        "id": 42,
        "name": "clara fayyad",
        "phoneNumber": "03456123",
        "email": "clarafayad@yahoo.com",
        "job": "none",
        "address": "kaftoun",
        "imageSrc": "http:\/\/localhost\/addressbook\/API\/uploads\/IMG_3340.JPG"
      },
      {
        "id": 43,
        "name": "karen fayyad",
        "phoneNumber": "78456654",
        "email": "karenfayyad@live.com",
        "job": "teacher",
        "address": "kaftoun",
        "imageSrc": "http:\/\/localhost\/addressbook\/API\/uploads\/Pink-Floyd-2019.png"
      }
    ],
   "count": 2
  }  
  ```
  ​

*   How does GETContacts.php work?

    1. The request is sent to this endpoint.

    2. Input is validated.

    3. Offset number is calculated for pagination (to limit the number of rows selected).

    4. The SQL SELECT query is prepared depending on the input. 

    5. The select query is executed to fetch the contacts and their images.

    6. Another query is executed to count the total number of matching rows for pagination purposes.

    7. The data is prepared and then returned by this endpoint.

       ---

       ​

       ### 2. POSTContact.php

    * This endpoint is used to add a contact to the database.
    * path: http://localhost/addressbook/API/POSTContact.php
    * HTTP methods:
      * POST
    * Request data:

    | Parameter   | Value  | Description                              | Required/Optional |
    | ----------- | ------ | ---------------------------------------- | ----------------- |
    | firstName   | String | The first name of the new contact. Ex: 'John' | Required          |
    | lastName    | String | The last name of the new contact. Ex: 'Smith' | Required          |
    | phoneNumber | String | The phone number of the new contact Ex: '03789123' | Required          |
    | email       | String | The email address of the new contact. Ex: 'john.smith@yahoo.com' | optional          |
    | address     | String | The address of the new contact. Ex: 'El Koura, North Governorate' | optional          |
    | job         | String | The job title of the new contact. Ex: 'CEO' | optional          |

    * Request Example:

      * CURL: 

        curl --request POST \
          --url 'http://localhost/addressbook/API/POSTContact.php?firstName=John&lastName=Smith&phoneNumber=03789123&email=john.smith%40yahoo.com&address=El%20Koura%2C%20North%20Governorate&job=CEO'

      * Response: 

        ```json
        {
          "message": "successfully added",
          "id": 89
        }
        ```

      ​

    * How does POSTContact.php work?

      1. The request data is sent to this endpoint 

      2. An error response is returned if the required data is not available.

      3. The phone number is validated by checking if it belongs to another contact.

      4. The contact is added to the Contact Table and then an appropriate image is added to the Image table. 

      5. If there isn't any error, this endpoint sends as a response a success message with the id of the inserted contact. Else, it sends an error message. 

         ---

         ​

      ### 3. DELETEContact.php

    * This endpoint deletes a contact from the database.

    * Path: http://localhost/addressbook/API/DELETEContact.php

    * HTTP methods:
        * GET

    * Request data:

| Parameter | Value  | Description                         | Required/Optional |
| --------- | ------ | ----------------------------------- | ----------------- |
| contactId | Number | The id of the contact to be deleted | Required          |

* Request Example:
  * CURL: curl --request GET \
      --url 'http://localhost/addressbook/API/DELETEContact.php?contactId=89'
  * Response: 

```json
"ok"
```
​

- How does DELETEContact.php work?

  1. The request data is sent to this endpoint 

  2. An error response is returned if the required data is not available.

  3. The contact is deleted from Image, Relationship and Contact Tables.

  4. If there isn't any error, this endpoint sends as a response a success message  "ok". Else, it sends an error message. 

     ------

     ​


### 4. PATCHContact.php

- This endpoint is used to update the contact's phone number. 
- Path: http://localhost/addressbook/API/PATCHContact.php
- HTTP methods:
  - GET
- Request data:

| Parameter      | Value  | Description                          | Required/Optional |
| -------------- | ------ | ------------------------------------ | ----------------- |
| contactId      | Number | The id of the contact to be updated. | Required          |
| oldPhoneNumber | String | The contact's old phone number.      | Required          |
| newPhoneNumber | String | The contact's new phone number.      | Required          |

- Request Example:

  - CURL: 

    curl --request GET \
      --url 'http://localhost/addressbook/API/PATCHContact.php?contactId=89&oldPhoneNumber=03789123&newPhoneNumber=03123565'

  - Response: 

    ```json
    "updated successfully"
    ```

  ​

- How does PATCHContact.php work?

  1. The request data is sent to this endpoint 

  2. An error response is returned if the required data is not available.

  3. The new phone number is validated: it should be different than the old phone number and should not exist for another contact. 

  4. If there isn't any error, this endpoint sends as a response a success message. Else, it sends an error message. 

     ------

     ​

### 5. PATCHImage.php

- This endpoint is used to update the contact's image. 
- Path: http://localhost/addressbook/API/PATCHImage.php
- HTTP methods:
  - POST
- Request data:

| Parameter | Value  | Description           | Required/Optional |
| --------- | ------ | --------------------- | ----------------- |
| file      | File   | The image file.       | Required          |
| contactId | Number | The id of the contact | Required          |

- How does PATCHImage.php work?

  1. The request data is sent to this endpoint 
  2. An error response is returned if the required data is not available.
  3. The file's extension is validated.
  4. The file is uploaded.
  5. The image path is updated in the Image table.
  6. If there isn't any error, this endpoint returns as a response a success message and the path to the new image (fileDestination). Else, it returns an error message.

---

### 6. GETRelationships.php

- This endpoint is used to fetch a contact's relationships.
- Path: http://localhost/addressbook/API/GETRelationships.php
- HTTP methods:
  - GET
- Request data:

| Parameter | Value  | Description                              | Required/Optional |
| --------- | ------ | ---------------------------------------- | ----------------- |
| id        | Number | The id of the contact whose relationship you are fetching. | Required          |

- Request Example:

  - CURL: 

    curl --request GET \
      --url 'http://localhost/addressbook/API/GETRelationships.php?id=93'

  - Response: 

    ```json
    [
      {
        "type": "friend",
        "ContactId": 71,
        "ContactName": "Georges Daher",
        "ContactPhoneNumber": "03456987"
      },
      {
        "type": "cousin",
        "ContactId": 76,
        "ContactName": "Jihad Fares",
        "ContactPhoneNumber": "03456321"
      }
    ]
    ```

  ​

- How does GETRelationships.php work?

  1. The request data is sent to this endpoint 
  2. An error response is returned if the required data is not available.
  3. Details about the contact's relationships are fetched from the database.
  4. If there is no error, the fetched data is sent as a response.

------

### 7. POSTRelationship.php

- This endpoint is used to add a relationship.
- Path: http://localhost/addressbook/API/POSTRelationship.php
- HTTP methods:
  - POST
- Request data:

| Parameter | Value  | Description                              | Required/Optional |
| --------- | ------ | ---------------------------------------- | ----------------- |
| type      | String | The type of the relationship. Ex:'friend', 'cousin'... | Required          |
| fromId    | Number | The id of the original contact.          | Required          |
| toId      | Number | The id of the other contact i.e. the one you are making a relationship to. Ex: Suppose you are adding a relationship to 'John'. John has a friend named Jad. So the fromId is John's ID and the toID is Jad's. | Required          |

- Request Example:

  - CURL: 

    curl --request POST \
      --url 'http://localhost/addressbook/API/POSTRelationship.php?type=friend&toId=1&fromId=2'

  - Response: 

    ```json
    {
      "message": "relationship successfully added",
      "ContactName": "John Smith",
      "ContactPhoneNumber": "78654312"
    }
    ```

  ​

- How does POSTRelationship.php work?

  1. The request data is sent to this endpoint 
  2. An error response is returned if the required data is not available.
  3. The 'toId' is validated by making sure it exists among the contacts.
  4. The relationship is validated by making sure it does not already exist.
  5. The relationship is added and the name and phone number of the contact to whom you are making a relationship (toId) is fetched.
  6. If there is no error, the fetched data is sent as a response along with a success message.

------

### 8. DELETERelationship.php

- This endpoint is used to delete a relationship.
- Path: http://localhost/addressbook/API/DELETERelationship.php
- HTTP methods:
  - GET
- Request data:

| Parameter        | Value  | Description                              | Required/Optional |
| ---------------- | ------ | ---------------------------------------- | ----------------- |
| contactId1       | Number | The id of contact 1 (one contact in the relationship) | Required          |
| contactId2       | Number | The id of contact 2 (the other contact in the relationship) | Required          |
| relationshipType | String | The relationship type i.e 'friend', 'family'... | Required          |

- How does DELETERelationship.php work?

  1. The request data is sent to this endpoint 
  2. An error response is returned if the required data is not available.
  3. The relationship is deleted from Relationship table.
  4. If there is no error,  a success response message is returned. 

------



### <u>N.B</u>:

For all endpoints there is an error response code 400. This error happens in the following possible situations:

1. One of the request parameters is missing.

2. PHP error caused by a failed connection to the database.

3. PHP error caused by a failed execution of an SQL query.

   ...

   However, in any case of an erroneous event, a proper message is echoed by PHP. For example: 'missing parameters'. 

   ---

   ​