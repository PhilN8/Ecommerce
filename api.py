import requests


class BearerAuth(requests.auth.AuthBase):
    def __init__(self, token):
        self.token = token

    def __call__(self, r):
        r.headers["authorization"] = "Bearer " + self.token
        return r


url = "http://localhost:8080/users/1"
response = requests.get(
    url,
    auth=BearerAuth(
        "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6InBoaWxvMTIzNCIsImlhdCI6MTY0NDMyODg3MSwiZXhwIjoxNjQ0MzMyNDcxfQ.qwkGNn66tMkmsaP-a0Z2NQO6RDRbVLF0UX_LVGx8rwc"
    ),
).json()

print(response)
