import falcon, psycopg2, os, platform
from core.config import AppState
from database.context import PGDatabase

from routes.login import LoginController

db = PGDatabase()
db.connect()

api = application = falcon.App(middleware=[])

api.add_route('/api/auth/login', LoginController())

if platform.system() == "Linux":
    import bjoern
    bjoern.run(api, host=AppState.HOST, port=AppState.PORT, reuse_port=True)
else:
    # import uvicorn
    # uvicorn.run(api, host=AppState.HOST, port=AppState.PORT, log_level=AppState.LOGGING_LEVEL)
    import waitress
    waitress.serve(api, host=AppState.HOST, port=AppState.PORT)