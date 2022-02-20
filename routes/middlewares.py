import falcon
from core.utils import api_message
from core.config import AppState

class DebugMiddleware(object):

    def process_request(self, req, resp):
        api_message('d', f'[MIDDLEWARE][{req.path}] <= {req.media}')
        #print(f'COOKIE : {req.cookies}')

    def process_response(self, req, resp, resource, req_succeeded):
        api_message('d', f'[MIDDLEWARE][{req.path}] => {resp.media}')