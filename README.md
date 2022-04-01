<p align="center"><img src="assets/images/banner.jpg" width="1700"></p>

# API Relevant

Rest API untuk menampilkan daftar event-event yang dapat diikuti relawan, selain itu dapat juga membuat event dengan ketentuan harus berupa organisasi.

# Konfigurasi

- Ketikkan pada terminal `composer update`
- Ketikkan pada terminal `php artisan storage:link`
- Ketikkan pada terminal `php artisan migrate:fresh --seed`
- Terakhir, ketikkan pada terminal `php artisan serve --host=0.0.0.0`

# Testing

<details>
<summary> API Category</summary>

- Get Category

<details>
  <summary>(GET) http://127.0.0.1:8000/api/category</summary>
  
  ```json
  {
    "message": "Success Get Data",
    "data": [
        {
            "id": 9,
            "name": "Kesehatan",
            "description": "Event berkaitan dengan Kesehatan",
            "created_at": "2021-10-03T14:30:46.000000Z",
            "updated_at": "2021-10-03T14:30:46.000000Z"
        },
        {
            "id": 10,
            "name": "Pendidikan",
            "description": "Event berkaitan dengan Pendidikan",
            "created_at": "2021-10-03T14:30:59.000000Z",
            "updated_at": "2021-10-03T14:30:59.000000Z"
        },
        {
            "id": 13,
            "name": "TEST",
            "description": "tEST",
            "created_at": "2021-10-03T14:50:11.000000Z",
            "updated_at": "2021-10-03T14:50:11.000000Z"
        }
    ]
}
```

</details>

<br>

- Get Category (id)

<details>
  <summary>(GET) http://127.0.0.1:8000/api/category/9</summary>

```json
{
    "message": "Success Get Data",
    "data": {
        "id": 9,
        "name": "Kesehatan",
        "description": "Event berkaitan dengan Kesehatan",
        "created_at": "2021-10-03T14:30:46.000000Z",
        "updated_at": "2021-10-03T14:30:46.000000Z"
    }
}

```
</details>


<br>

- Create

<details>
  <summary>(POST) http://127.0.0.1:8000/api/category</summary>

```json
{
    "message": "Create Success",
    "data": {
        "name": "TEST",
        "description": "tEST",
        "updated_at": "2021-10-03T15:53:19.000000Z",
        "created_at": "2021-10-03T15:53:19.000000Z",
        "id": 14
    }
}

```
</details>

<br>

- Update

<details>
  <summary>(PUT) http://127.0.0.1:8000/api/category</summary>

```json
{
    "message": "Update Success",
    "data": {
        "id": 9,
        "name": "Olahraga",
        "description": "Olahraga",
        "created_at": "2021-10-03T14:30:46.000000Z",
        "updated_at": "2021-10-03T15:56:30.000000Z"
    }
}

```
</details>

<br>

- Delete

    <details>
    <summary>(DELETE) http://127.0.0.1:8000/api/category</summary>

    ```json
    {
        "message": "Delete Success",
        "data": {
            "id": 9,
            "name": "Olahraga",
            "description": "Olahraga",
            "created_at": "2021-10-03T14:30:46.000000Z",
            "updated_at": "2021-10-03T15:56:30.000000Z"
        }
    }

    ```
    </details>

</details>

<br>

# Issues

Please file any issues, bugs or feature request as an issue on <a href="https://github.com/zgramming/Relawan-TWS/issues"><b> Github </b></a>

# Contributing

<a href="https://github.com/zgramming/Relawan-TWS/pulls" target="_blank">Pull requests</a> are welcome. For major changes, please open an issue first to discuss what you would like to change.

<br>
