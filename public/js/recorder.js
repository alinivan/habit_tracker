//webkitURL is deprecated but nevertheless
URL = window.URL || window.webkitURL;

var gumStream;
var rec;
var input;

var AudioContext = window.AudioContext || window.webkitAudioContext;
var audioContext;

$('#recordButton').click(function () {
    let recording= $('#recordButton').data('recording');

    if (recording) {
        stopRecording();
        $('#recordButton').data('recording', 0);
    } else {
        startRecording();
        $('#recordButton').data('recording', 1);
    }
});

function startRecording() {
    console.log('starting rec');
    var constraints = { audio: true, video:false }

    navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
        audioContext = new AudioContext();
        gumStream = stream;

        input = audioContext.createMediaStreamSource(stream);
        rec = new Recorder(input,{numChannels:1})
        rec.record()
    }).catch(function(err) {
        //$('#recordButton').data('recording', 0);
    });
}

function stopRecording() {
    console.log('stopping rec');
    rec.stop();
    gumStream.getAudioTracks()[0].stop();
    rec.exportWAV(createDownloadLink);
}

function createDownloadLink(blob) {
    console.log('saving');
    var filename = new Date().toISOString();

    var xhr=new XMLHttpRequest();
    xhr.onload=function(e) {
        if(this.readyState === 4) {
            console.log("Server returned: ",e.target.responseText);
        }
    };
    var fd=new FormData();
    fd.append("audio_data",blob, filename);
    xhr.open("POST","tracker/upload",true);
    xhr.send(fd);
}