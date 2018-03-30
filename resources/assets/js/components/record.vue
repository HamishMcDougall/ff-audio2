<template>

      <div class="main">


          <button type="button" class="btn btn-secondary" v-on:click="toggleRecording">
            <span class="" v-show="isRecording"></span>
              <span class="oi oi-microphone" v-show="!isRecording"></span>
            <span v-show="!isRecording">Start recording</span>
            <span v-show="isRecording">Stop recording</span>
          </button>




        <button type="button" class="btn btn-secondary" v-if="dataUrl.length>0" v-on:click="togglePlay">
            <span class="oi oi-play-circle"> </span>
              Play recording
        </button>
        <button type="button" class="btn btn-secondary" v-if="dataUrl.length>0" v-on:click="deleteRecording">
          <span class="oi oi-trash"></span>
            Delete recording
        </button>
        <button type="button" class="btn btn-secondary" v-if="dataUrl.length>0">
          <span class="oi oi-task"></span>
            Save recording
          </button>

        <audio id="audio" preload="auto" v-if="dataUrl.length>0" v-bind:src="dataUrl"></audio>

      </div>

</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')

        },
        data: function () {
          return {
         isRecording: false,
         audioRecorder: null,
         recordingData: [],
         dataUrl: ''
       };
    },
    methods:
    {
      toggleRecording: function() {
           this.isRecording = !this.isRecording;
        var that = this;

        //check toggle of the button
      if(this.isRecording){
          if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
         console.log('getUserMedia supported.');
         navigator.mediaDevices.getUserMedia (
            // constraints - only audio needed for this app
            {
               audio: true
            })

            // Success callback
            .then(function(stream) {
              that.stream = stream;
              that.audioRecorder = new MediaRecorder(stream, {
              });
              that.audioRecorder.start();
              console.log('Media recorder started');

            })

            // Error callback
            .catch(function(err) {
               console.log('The following getUserMedia error occured: ' + err);
            }
         );
      } else {
       console.log('getUserMedia not supported on your browser!');
      }

}
//stop recording
else{
    this.audioRecorder.stop();
        this.audioRecorder.ondataavailable = function(event) {
        that.recordingData.push(event.data);
    }
    this.audioRecorder.onstop = function(event) {
        console.log('Media recorder stopped');
        var blob = new Blob(that.recordingData, { type: 'audio/ogg'});
        that.dataUrl = window.URL.createObjectURL(blob);
    }
  }

        },


        togglePlay: function() {
          console.log('Recording audio play');
          console.log(this.dataUrl);
          var audioElement = document.getElementById("audio");
          audioElement.play();

/*pause stuff

this is a hanges

      var onplaying = true;
      var onpause = false;


      // On video playing toggle values
      audioElement.onplaying = function() {
          onplaying = true;
          onpause = false;
      };

      // On video pause toggle values
      audioElement.onpause = function() {
          onplaying = false;
          onpause = true;
      };

      // Play video function
      function playVid() {
          if (audioElement.paused && !onplaying) {
              audioElement.play();
          }
      }

      // Pause video function
      function pauseVid() {
          if (!audioElement.paused && !onpause) {
              audioElement.pause();
          }
      }
      */



  },

        submitRecording: function() {
        },
        deleteRecording: function() {
          console.log('Deleted recording');
            this.isRecording = false;
            this.recordingData = [];
            this.dataUrl = '';
        }
    },
    }
</script>
