import falcon, psycopg2, os, platform
from core.config import AppState
from database.context import PGDatabase

from routes.login import LoginController

db = PGDatabase()
db.connect()

api = application = falcon.App(middleware=[])

api.add_route('/api/auth/login', LoginController())