curl -v --request POST \
     --url https://api.mailersend.com/v1/email \
     --header 'Content-Type: application/json' \
     --header 'X-Requested-With: XMLHttpRequest' \
     --header 'Authorization: Bearer mlsn.5192df543e07292a8dfa540334a9cad60c8d7d73e4e0086219e18b59529e6950' \
     --data '{
        "from": {
            "email": "norelpy@test-yxj6lj9wxjx4do2r.mlsender.net",
            "name": "Burak"
        },
        "to": [
            {
                "email": "norelpy@test-yxj6lj9wxjx4do2r.mlsender.net",
                "name": "Burak"
            }
        ],
        "subject": "Test E-postası",
        "text": "Bu bir test e-postasıdır.",
        "html": "<p>Bu bir test e-postasıdır.</p>"
     }'
     
