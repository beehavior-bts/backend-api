import falcon, uuid
from core.config import AppState
from core.auth import *
from core.utils import *

"""
    Get account infos
"""

class AccountInfoController:
    
    def __init__(self):
        self._tokenizer = AccountAuthToken()

    def on_get(self, req, resp):
        token = req.get_cookie_values("Token-Account")[0]
        payload = self._tokenizer.decode(token)
        account_id = payload["id"]
        q1 = None
        q2 = None

        try:
            with AppState.Database.CONN.cursor() as cur:
                cur.execute("SELECT id, email, username, is_admin, phone FROM accounts WHERE id = %s", (account_id,))
                q1 = cur.fetchone()
        except Exception as e:
            api_message("d", f'Error while getting account infos : {e}')
            raise falcon.HTTPBadRequest()
        
        email = q1[1]
        username = q1[2]
        is_admin = bool(q1[3])
        phone = q1[4]
        hives = []
        
        try:
            with AppState.Database.CONN.cursor() as cur:
                cur.execute("SELECT id, name FROM hives WHERE f_owner = %s", (account_id,))
                q2 = cur.fetchall()
        except Exception as e:
            api_message("d", f'Error while getting hives infos : {e}')
            raise falcon.HTTPBadRequest()

        for i in q2:
            hives.append({"id": i[0], "name": i[1]})
        
        resp.media = {
            "title": "OK", 
            "description": "Sucess to get account and hives info", 
            "content": {
                "id": uuid.UUID(q1[0]).hex,
                "username": username,
                "email": email,
                "phone": phone,
                "is_admin": is_admin,
                "hives": hives
            }
        }
        resp.status = falcon.HTTP_200