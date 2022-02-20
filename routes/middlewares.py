import falcon
from core.utils import api_message
from core.config import AppState

class DebugMiddleware(object):

    def process_request(self, req, resp):
        api_message('d', f'[MIDDLEWARE][{req.path}] <= {req.media}')
        #print(f'COOKIE : {req.cookies}')

    def process_response(self, req, resp, resource, req_succeeded):
        api_message('d', f'[MIDDLEWARE][{req.path}] => {resp.media}')

class AcceptJSONMiddleware:

    def process_request(self, req, resp):

        if not req.client_accepts_json:
            raise falcon.HTTPNotAcceptable('This API only supports responses encoded as JSON.')
            
        if req.content_type == falcon.MEDIA_JSON:
            return

    def process_response(self, req, resp, resource, req_succeeded):
        resp.set_header('Accept', falcon.MEDIA_JSON)
        resp.set_header('X-Api-Version', ".".join(map(str, AppState.VERSION)))