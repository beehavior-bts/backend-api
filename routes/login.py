import falcon, uuid
from core.config import AppState
from core.auth import *
from core.utils import *

"""
    Process to login
"""

class LoginController:

    def __init__(self):
        self.tokenizer = AccountAuthToken()
        self.post_form = {
            "$schema": AppState.Tools.JSONSCHEMA_VERSION,
            "type": "object",
            "properties": {
                # "username"  : {"type": "string", "pattern": "^(?=[a-zA-Z0-9._]{3,24}$)(?!.*[_.]{2})[^_.].*[^_.]$"},
                "email"     : {"type": "string", "format": "email", "pattern": "^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$"},
                "password"  : {"type": "string", "pattern": "^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,60}$"}
            },
            "required": ["email", "password"]
        }

    def on_post(self, req, resp):
        resp.status = falcon.HTTP_400
        if api_validate_form(req.media, self.post_form):
            email       = req.media["email"]
            q1 = None
            with AppState.Database.CONN.cursor() as cur:
                cur.execute("SELECT id, email, username, password, is_admin FROM accounts WHERE email = %s", (email,))
                q1 = cur.fetchone()
            
            if q1 is None:
                resp.status = falcon.HTTP_BAD_REQUEST
                resp.media  = {'title': 'BAD_REQUEST', 'description': 'Email or password not found'}
                return

            db_id: uuid.UUID = uuid.UUID(q1[0]).hex
            db_fullname: str = q1[2]
            db_hash: str = q1[3]
            db_is_admin: bool = q1[4]

            if verify_argon_hash(db_hash, req.media["password"]):
                token: str = self.tokenizer.create({
                    "id": db_id,
                    "is_admin": db_is_admin
                }, duration_s=10800)

                print("success login (user_id={0} fullname={1})".format(q1[0], db_fullname))
                resp.status = falcon.HTTP_OK
                resp.set_header("Access-Control-Allow-Credentials", "true")
                resp.set_cookie(name="Token-Account", value=token, http_only=True, max_age=10800, secure=False if AppState.TAG in ["dev", "test"] else True, domain=".beehavior.com", same_site="Strict", path="/")
                resp.media  = {'title': 'OK', 'description': 'Success to login', 'content': {'id': db_id, 'username': db_fullname, 'token': token}}
                return

            else:
                resp.status = falcon.HTTP_BAD_REQUEST
                resp.media  = {'title': 'BAD_REQUEST', 'description': 'Email or password not found'}
                return


