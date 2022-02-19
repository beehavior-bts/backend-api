import argon2, falcon
from authlib import jose
from core.config import AppState

from cryptography.hazmat.backends import default_backend
from cryptography.hazmat.primitives.asymmetric import rsa
from cryptography.hazmat.primitives import serialization

def gen_account_keypair() -> list:
    """
    Generate 4096 RSA keypair for account
    . Return list of pem key in bytes
    - KEYPAIR[0] -> public key
    - KEYPAIR[1] -> private key
    """

    private_key = rsa.generate_private_key(
        public_exponent=65537,
        key_size=4096,
        backend=default_backend()
    )
    public_key = private_key.public_key()

    private_pem = private_key.private_bytes(
        encoding=serialization.Encoding.PEM,
        format=serialization.PrivateFormat.PKCS8,
        encryption_algorithm=serialization.NoEncryption()
    )
    public_pem = public_key.public_bytes(
        encoding=serialization.Encoding.PEM,
        format=serialization.PublicFormat.SubjectPublicKeyInfo
    )

    return [public_pem, private_pem] # str


class AccountAuthToken():

    def __init__(self, pubkey=None, privkey=None) -> None:
        self._pubkey = pubkey
        self._privkey = privkey

    def create(self, payload: dict, duration_s: int) -> str:
        # api_message("d", f'roles type : {type(roles)}')
        header = {"alg": AppState.AccountToken.TYPE}
        try:
            payload["exp"] = datetime.datetime.utcnow() + datetime.timedelta(seconds=duration)
        except Exception as e:
            print(f'Failed to deserialize payload : {e}')
            # api_message('d', f'Failed to deserialize payload : {e}')
        try:
            return jose.jwt.encode(
                header,
                payload,
                AppState.AccountToken.PRIVATE_KEY if AppState.AccountToken.TYPE == "RS256" else AppState.AccountToken.SECRET,
                check=False
            ).decode('utf-8')
        except Exception as e:
            # print(f'LOGGING : {AppState.LOGGING_ENABLE}\nSTDOUT : {AppState.STDOUT_ENABLE}\nSTDERR : {AppState.STDERR_ENABLE}')
            # api_message('d', f'{e}')
            # print(e)
            raise falcon.HTTPInternalServerError(title="account auth token", description=f'{e}')


    def decode(self, token: str) -> dict:
        try:
            token = bytes(token, encoding="utf-8")
            res = jose.jwt.decode(
                token, 
                AppState.AccountToken.PUBLIC_KEY if AppState.AccountToken.TYPE == "RS256" else AppState.AccountToken.SECRET
            )
            res.validate()
            # api_message("d", f'payload token content : {res}')
            return res
        except jose.errors.ExpiredTokenError as e:
            # api_message('w', "Connection Expired (Signature invalid)")
            raise falcon.HTTPUnauthorized(description='Signature expired. Please log in again.')
        except jose.errors.BadSignatureError as e:
            # api_message('w', "Signature Key Invalid. Please login again")
            raise falcon.HTTPForbidden(description='Invalid token. Please log in again.')
        except Exception as e:
            # api_message('w', f"unknow exception on decode token function : {e}")
            raise falcon.HTTPForbidden(description="Impossible d'authentifier l'utilisateur")



def gen_argon_hash(_password: str) -> str:
    hasher = argon2.PasswordHasher()
    return hasher.hash(_password)

def verify_argon_hash(_hash: str, _password: str) -> bool:
    hasher = argon2.PasswordHasher()
    try:
        return hasher.verify(_hash, _password)
    except Exception as e:
        print(e)
        return False


