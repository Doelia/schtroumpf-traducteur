import './style.scss'

import {gsap} from "gsap";
import {TextPlugin} from "gsap/TextPlugin";

gsap.registerPlugin(TextPlugin)

let pendingTimeout = null;

async function translate(sentence) {

    if (!sentence) return '';

    if (pendingTimeout) {
        clearTimeout(pendingTimeout);
    }

    return new Promise((resolve, reject) => {
        pendingTimeout = setTimeout(async () => {
            const url = '/api/translate?sentence=' + encodeURIComponent(sentence);
            const response = await fetch(url);
            if (!response.ok) {
                reject('Error');
            }
            const data = await response.json();
            resolve(data.final_sentence);
        }, 200);
    });

}

function setSpinnerState(isSpinning) {
    document.getElementById('spinner').style.display = isSpinning ? 'inline-block' : 'none';
}

async function compute(sentence) {
    setSpinnerState(true);
    const newSentence = await translate(sentence);
    gsap
        .fromTo("#sentence_output", {}, {
            text: {
                value: newSentence.replace(/(?:\r\n|\r|\n)/g, '<br>')
            },
            ease: 'power1',
            onComplete: function () {
                setSpinnerState(false);
            }
        });
}

if (document.getElementById('sentence_input')) {

    document.getElementById('btn_random').addEventListener('click', function (e) {
        const random = SENTENCES_EXAMPLES[Math.floor(Math.random() * SENTENCES_EXAMPLES.length)];
        document.getElementById('sentence_input').value = random;
        compute(random);
    });

    compute(document.getElementById('sentence_input').value);

    document.getElementById('sentence_input').addEventListener('input', function (e) {
        const sentence = e.target.value;
        compute(sentence);
    });

}
