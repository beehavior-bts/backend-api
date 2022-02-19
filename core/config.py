import os, pathlib, git, logging

GIT_CURRENT_TAG: str
try:
    REPOSITORY          = git.Repo(pathlib.Path().absolute())
    GIT_CURRENT_TAG     = str(next((tag for tag in REPOSITORY.tags if tag.commit == REPOSITORY.head.commit), "v0.0.1-dev"))
except Exception as e:
    print(f'[WARN] Failed to get Git repository, error : {e}')
    GIT_CURRENT_TAG = "v0.0.1-dev"


LOG_LEVEL = {
    "critical"  : logging.CRITICAL,
    "error"     : logging.ERROR,
    "warning"   : logging.WARNING,
    "info"      : logging.INFO,
    "debug"     : logging.DEBUG
}

class AppState:
    """ Describe application variable state """
    PID: int        = os.getpid()
    PATH: str       = pathlib.Path().absolute()

    TAG: str        = GIT_CURRENT_TAG.split('-')[1]
    VERSION: list   = GIT_CURRENT_TAG[1:].split('-')[0].split(".")

    HOST: str       = os.environ.get('APP_HOST', '127.0.0.1')
    PORT: int       = int(os.environ.get('APP_PORT', 8080))

    LOGGING_LEVEL   = LOG_LEVEL["debug"]
    LOGGING_ENABLE: bool  = False
    STDERR_ENABLE: bool   = True
    STDOUT_ENABLE: bool   = True

    class Tools:
        """ Describe other package configuration """
        JSONSCHEMA_VERSION = "http://json-schema.org/draft-07/schema#"

    class Database:
        """ Describe SQL Database Credentials """
        CONN = None
        TYPE: str = "postgres"
        NAME: str = os.environ.get("DB_NAME", "beehavior")
        HOST: str = os.environ.get("DB_HOST", "127.0.0.1")
        PORT: int = int(os.environ.get("DB_PORT", 5432))
        USER: str = os.environ.get("DB_USER", "beehavior")
        PASS: str = os.environ.get("DB_PASS", "beehavior")

    class AccountToken:
        """ Describe Account token authentification controller credentials """
        TYPE: str       = 'HS256'
        SECRET: str     = 'secret'
        PUBLIC: bytes   = None
        PRIVATE: bytes  = None

# Apply a configuration according to the tag
if AppState.TAG in 'dev':
    # AppState.LOGGING_LEVEL = logging.DEBUG
    AppState.AccountToken.TYPE = 'RS256'
    AppState.AccountToken.SECRET = 'secret'
elif AppState.TAG in 'test':
    AppState.AccountToken.TYPE = 'HS256'
    # AppState.LOGGING_LEVEL = logging.DEBUG
elif AppState.TAG in ['alpha', 'beta', 'stable']:
    AppState.Database.TYPE = "postgresql"
    """
    AppState.LOGGING_LEVEL  = logging.WARNING
    AppState.LOGGING_ENABLE = True
    AppState.STDERR_ENABLE  = False
    AppState.STDOUT_ENABLE  = False
    """