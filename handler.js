'use strict';

var child_process = require('child_process');

module.exports.stickies = (event, context, callback) => {

  var post_data = event.body;

  var strToReturn = '';

  var php = './php';

  // workaround to get 'sls invoke local' to work
  if (typeof process.env.PWD !== "undefined") {
    php = 'php';
  }

  var proc = child_process.spawn(php, [ "public/index.php", post_data, { stdio: 'inherit' } ]);

  proc.stdout.on('data', function (data) {
    var dataStr = data.toString()
    strToReturn += dataStr
  });

  proc.stderr.on('data', function (data) {
    console.log(`stderr: ${data}`);
    strToReturn += data;
  });

  proc.on('close', function(code) {
    if(code !== 0) {
      return callback(new Error(`Process exited with non-zero status code ${code}, ${strToReturn}`));
    }

    const response = {
      statusCode: 200,
      body: strToReturn
    };

    callback(null, response);
  });
};
