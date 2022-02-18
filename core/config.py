import os, pathlib, git

GIT_CURRENT_TAG: str
try:
    REPOSITORY          = git.Repo(pathlib.Path().absolute())
    GIT_CURRENT_TAG     = str(next((tag for tag in REPOSITORY.tags if tag.commit == REPOSITORY.head.commit), "v0.0.1-dev"))
except Exception as e:
    print(f'[WARN] Failed to get Git repository, error : {e}')
    GIT_CURRENT_TAG = "v0.0.1-dev"


class AppState:
    """ Describe application variable state """
    PID: int        = os.getpid()
    PATH: str       = pathlib.Path().absolute()

    TAG: str        = GIT_CURRENT_TAG.split('-')[1]
    VERSION: list   = GIT_CURRENT_TAG[1:].split('-')[0].split(".")

    HOST: str       = os.environ.get('APP_HOST', '127.0.0.1')
    PORT: int       = int(os.environ.get('APP_PORT', 8080))