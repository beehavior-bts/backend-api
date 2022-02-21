# REST API Documentation

## Login to an account

### Request

`POST /api/auth/login`

    curl -X POST -i -H 'Content-Type: application/json' http://beehavior.com/api/auth/login -d '{"email": "admin@beehavior.com", "password": "Patate#12345"}'

### Response

    HTTP/1.1 200 OK
    Server: nginx/1.21.4
    Date: Mon, 21 Feb 2022 14:05:32 GMT
    Content-Type: application/json
    Content-Length: 959
    Connection: keep-alive
    access-control-allow-credentials: true
    set-cookie: Token-Account=<YOUR_TOKEN>; Domain=.beehavior.com; HttpOnly; Max-Age=10800; Path=/; SameSite=Strict

    {"title": "OK", "description": "Success to login", "content": {"id": "513cbde1c5f64315b88e75fa6cd71dc7", "username": "Admin", "token": "<YOUR_TOKEN>"}}