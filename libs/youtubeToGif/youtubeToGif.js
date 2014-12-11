//////////////////////////////////////////////////////////////
var config = require("./libs/config.json");
var DEBUG = config.debug;

//////////////////////////////////////////////////////////////
var tools = require("./libs/tools").init(DEBUG);
var youtubedl = require("youtube-dl");
var gifify = require("gifify");
var fs = require("fs");
var path = require("path");

//////////////////////////////////////////////////////////////
var videoID = process.argv[2];
var width = parseInt(process.argv[3]) || 300;
var offset = parseInt(process.argv[4]) || 0;
var duration = parseInt(process.argv[5]) || 10;

//////////////////////////////////////////////////////////////
var video = youtubedl("http://www.youtube.com/watch?v="+videoID, ["--max-quality=18"], { cwd: __dirname });
var videoFile = path.join(__dirname, config.dataPath, videoID+".mp4");
var gifFile = path.join(__dirname, config.dataPath, videoID+".gif");
var size = 0;
var pos = 0;

//////////////////////////////////////////////////////////////
if(!fs.existsSync(videoFile)) {
	video.on("info", function(info) {
		tools.info("download started", videoFile);
		tools.info("file name", info.filename);
		tools.info("file size", info.size);
		size = info.size;
		video.pipe(fs.createWriteStream(videoFile));
	});

	video.on("error", function(data) {
		tools.error("video download error",data);
		process.exit(1);
	});

	video.on("data", function(data) {
		pos += data.length;
		if (size) {
			var percent = (pos / size * 100).toFixed(2);
			tools.info("downloading",percent,"%",videoFile);
		}
	});

	video.on("end", function() {
		tools.info("video downloaded", size, videoFile);
		processGif();
	});
}
else {
	processGif();
}

//////////////////////////////////////////////////////////////
function processGif() {
	var gif = fs.createWriteStream(gifFile);
	var options = {
		resize: width+":-1",
		from:offset,
		to:offset+duration,
		compress:20
	};
	gifify(videoFile, options).pipe(gif);
	tools.info("gif created", gifFile);
}