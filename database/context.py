import psycopg2, time
from core.utils import api_message
from core.config import AppState

class PGDatabase:

    def connect_to_api(self) -> bool:
        for i in range(2, 6):
            try:
                AppState.Database.CONN = psycopg2.connect(
                    host=AppState.Database.HOST,
                    user=AppState.Database.USER,
                    password=AppState.Database.PASS,
                    port=AppState.Database.PORT,
                    dbname=AppState.Database.NAME
                )
                api_message("i", f'encoding : {AppState.Database.CONN.encoding}')
                api_message("d", f'Success to connect to PostgreSQL database')
                return True
            except psycopg2.DatabaseError as e:
                api_message("d", f'Retry postgres connection n°{i}, error : {e}')
                AppState.Database.CONN = None
                time.sleep(2)
        api_message("d", f'Failed to connect to postgres server')
        exit(1)
    
    def get_connexion(self):
        return psycopg2.connect(
            host=AppState.Database.HOST,
            user=AppState.Database.USER,
            password=AppState.Database.PASS,
            port=AppState.Database.PORT,
            dbname=AppState.Database.NAME
        )