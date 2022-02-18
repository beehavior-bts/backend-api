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
    
    """ 
    RSAKeys = OpenSSL.crypto.PKey()
    RSAKeys.generate_key(OpenSSL.crypto.TYPE_RSA, 4096)
    privkey = OpenSSL.crypto.dump_privatekey(OpenSSL.crypto.FILETYPE_PEM, RSAKeys, cipher=None, passphrase=None)
    pubkey = OpenSSL.crypto.dump_publickey(OpenSSL.crypto.FILETYPE_PEM, RSAKeys)
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