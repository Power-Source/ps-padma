// CodeMirror, copyright (c) by Marijn Haverbeke and others
// Distributed under an MIT license: http://codemirror.net/LICENSE

// declare global: tern, server

var server;

this.onmessage = function(e) {
  var data = e.data;
  switch (data.type) {
  case "init": return startServer(data.defs, data.plugins, data.scripts);
  case "add": return server.addFile(data.name, data.text);
  case "del": return server.delFile(data.name);
  case "req": return server.request(data.body, function(err, reqData) {
    postMessage({id: data.id, body: reqData, err: err && String(err)});
  });
  case "getFile":
    if (typeof data.id != "number" || data.id < 1 ||
        data.id != Math.floor(data.id) ||
        !Object.prototype.hasOwnProperty.call(pending, data.id)) {
      throw new Error("Unknown request id: " + data.id);
    }
    var c = pending[data.id];
    delete pending[data.id];
    if (typeof c != "function") throw new Error("Invalid request callback");
    return c(data.err, data.text);
  default: throw new Error("Unknown message type: " + data.type);
  }
};

var nextId = 0, pending = Object.create(null);
function getFile(file, c) {
  if (typeof c != "function") throw new Error("Invalid file callback");
  postMessage({type: "getFile", name: file, id: ++nextId});
  pending[nextId] = c;
}

function startServer(defs, plugins, scripts) {
  if (scripts) importScripts.apply(null, scripts);

  server = new tern.Server({
    getFile: getFile,
    async: true,
    defs: defs,
    plugins: plugins
  });
}

this.console = {
  log: function(v) { postMessage({type: "debug", message: v}); }
};
