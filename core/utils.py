import datetime, falcon, jsonschema, sys, os, random
from core.config import AppState

def eprint(*args, **kwargs) -> None:
    """ Print stderr """
    print(*args, file=sys.stderr, **kwargs)


def api_message(type: str, msg: str, log: bool = True) -> None:

    color: str = ""
    end: str = "\x1b[0m"
    if type == 'c':
        if AppState.LOGGING_ENABLE and log:
            logging.critical(msg)
        color = "\x1b[7;35;40m"
    elif type == 'e':
        if AppState.LOGGING_ENABLE and log:
            logging.error(msg)
        color   = "\x1b[5;30;41m"
    elif type == 'w':
        if AppState.LOGGING_ENABLE and log:
            logging.warning(msg)
        color = "\x1b[5;30;43m"
    elif type == 'i':
        if AppState.LOGGING_ENABLE and log:
            logging.info(msg)
        color    = "\x1b[5;30;47m"
    elif type == 'd':
        if AppState.LOGGING_ENABLE and log:
            logging.debug(msg)
        color   = "\x1b[5;30;44m"
    else:
        if AppState.STDERR_ENABLE:
            eprint(msg)

    if type in ['c', 'e'] and AppState.STDERR_ENABLE:
        eprint("{0}[{1}]{2} {3}".format(color, datetime.datetime.now().strftime("%d-%m-%Y %H:%M:%S"), end, msg))

    if type in ['w', 'i', 'd'] and AppState.STDOUT_ENABLE:
        print("{0}[{1}]{2} {3}".format(color, datetime.datetime.now().strftime("%d-%m-%Y %H:%M:%S"), end, msg))


def api_validate_form(media: dict, schema: dict) -> bool:
    """ Valide http json form and catch them """
    try:
        jsonschema.validate(media, schema)
    except Exception as e:
        api_message('w', "Request form not found")
        raise falcon.HTTPBadRequest(title="BAD_REQUEST", description="Request form not found")
    else:
        return True

def get_keypair_in_db(name: str) -> list:
    q1 = None
    with AppState.Database.CONN.cursor() as cur:
        cur.execute("SELECT public_key, private_key FROM keypairs WHERE type = %s", (name,))
        q1 = cur.fetchone()
    
    if q1 is None:
        raise RuntimeError(f'Failed to get keypair for {name}')
    
    return [q1[0], q1[1]]