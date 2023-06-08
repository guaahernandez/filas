let synth = speechSynthesis;

function textToSpeech(text){
    speechSynthesis.speak(new SpeechSynthesisUtterance("hello world"));
    // let utterance = new SpeechSynthesisUtterance(text);
    // for(let voice of synth.getVoices()){
    //     if(voice.name === 'Google español de Estados Unidos'){
    //         utterance.voice = voice;            
    //     }
    // }    
    // synth.speak(utterance);
}
