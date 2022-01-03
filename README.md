# ELK Demo


# Practice
## I. Ping & Health check
### 1. Ping Elasticsearch
```sh
$ curl -X GET "http://localhost:9200/"
```

### 2. Check Health Elasticsearch
```sh
$ curl -X GET "http://localhost:9200/_cat/health?v"
```

## II. Setting
### 99. Setting Index Allow "read-only / allow delete"
```sh
$ curl -X PUT "http://localhost:9200/products/_settings?pretty" -H 'Content-Type: application/json' -d '
{
  "index.blocks.read_only_allow_delete": null
}'
```

## III. CRUD index
### 1. Get Index Exists
```sh
$ curl -X GET "http://localhost:9200/_cat/indices?v"
```

### 2. Create Index
```sh
$ curl -X PUT "http://localhost:9200/products?pretty"
$ curl -X PUT "http://localhost:9200/soccers?pretty"
```

### 3. Detele Index
```sh
$ curl -X DELETE "http://localhost:9200/soccers?pretty"
```

## IV. Manage data in index
### 1. Get data to Index
```sh
$ curl -X GET "http://localhost:9200/products/_doc/1"
```

### 2. Store data to Index
```sh
$ curl -X PUT "http://localhost:9200/products/_doc/2" -H 'Content-Type: application/json' -d'
{
    "fullname": "Duc Toan",
    "age": 30
}'
```

### 3. Update data to Index
```sh
$ curl -X PUT "http://localhost:9200/products/_doc/2" -H 'Content-Type: application/json' -d'
{
    "fullname": "Duc Toan",
    "age": 30,
    "subject": [
        "toan",
        "van",
        "anh"
    ]
}'
```

### 4. Delete data to Index
```sh
$ curl -X DELETE "http://localhost:9200/products/_doc/2"
```

### 5. Update or Create mutil data to Index
```sh
$ curl -X POST "http://localhost:9200/_bulk?pretty" -H 'Content-Type: application/json' --data-binary "@opt/vietnam-football.json"
$ curl -X POST "http://localhost:9200/accounts/_bulk?pretty" -H 'Content-Type: application/json' --data-binary "@opt/accounts.json"
```

## V. Search data index
### 1. Get all data to index
```sh
$ curl -X GET "http://localhost:9200/accounts/_search?pretty"
$ curl -X GET "http://localhost:9200/accounts/_search?pretty=true&q=*:*"
```

### 2. Search data
```sh
# Seach match_all (_source, sort, size, from)
$ curl -X GET "http://localhost:9200/accounts/_search?pretty" -H 'Content-Type: application/json' -d'
{
    "query": {"match_all": {}},
    "_source": ["account_number", "balance"],
    "sort": [
        {
            "balance": {
                "order": "desc"
            }
        }
    ],
    "size": 2,
    "from": 10
}'
```

```sh
# Seach match (age)
$ curl -X GET "http://localhost:9200/accounts/_search?pretty" -H 'Content-Type: application/json' -d'
{
    "query": {"match": {
        "age": 34
    }},
    "_source": ["account_number", "balance", "age"],
    "sort": [
        {
            "balance": {
                "order": "desc"
            }
        }
    ],
    "size": 2,
    "from": 10
}'
```

```sh
# Search logic (must, should, must_not)
$ curl -X GET "http://localhost:9200/accounts/_search?pretty" -H 'Content-Type: application/json' -d'
{
    "query": {
        "bool": {
            "should": [
                {"match": {
                    "address": "mill"
                }},
                {"match": {
                    "address": "lane"
                }}
            ],
            "must_not": [
                {"match": {
                    "state": "IL"
                }}
            ]
        }
    },
    "_source": ["account_number", "balance", "address", "state"],
    "sort": [
        {
            "balance": {
                "order": "desc"
            }
        }
    ]
}'
```


# Reference
1. [Giới thiệu và cài đặt Elasticsearch và Kibana bằng Docker](https://xuanthulab.net/gioi-thieu-va-cai-dat-elasticsearch-va-kibana-bang-docker.html)
2. [Cài đặt Elasticsearch trên CentOS](https://xuanthulab.net/cai-dat-elasticsearch-tren-centos.html)
