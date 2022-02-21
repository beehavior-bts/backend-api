import falcon, psycopg2, os, platform
from core.config import AppState
from core.utils import *
from database.context import PGDatabase

from routes.middlewares import *
from routes.login import LoginController
from routes.account_info import AccountInfoController

db = PGDatabase()
db.connect_to_api()

account_auth = get_keypair_in_db("account_auth")
AppState.AccountToken.PUBLIC = account_auth[0]
AppState.AccountToken.PRIVATE = account_auth[1]

api = application = falcon.App(middleware=[DebugMiddleware()], cors_enable=True)

api.add_route("/api/auth/login", LoginController())
api.add_route("/api/account/info", AccountInfoController())