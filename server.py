import time, sys, cherrypy, os
from paste.translogger import TransLogger

def run_server(app):
 
    # Enable WSGI access logging via Paste
    app_logged = TransLogger(app)
    # Mount the WSGI callable object (app) on the root directory
    cherrypy.tree.graft(app_logged, '/')
 
    # Set the configuration of the web server
    cherrypy.config.update({
        'engine.autoreload.on': True,
        'log.screen': True,
        'server.socket_port': 10000,
        'server.socket_host': '0.0.0.0'
    })
 
    # Start the CherryPy WSGI web server
    cherrypy.engine.start()
    print("Server Started")
    cherrypy.engine.block()
 