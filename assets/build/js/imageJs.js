// Charger les modules
Promise.all([
    faceapi.nets.faceRecognitionNet.loadFromUri("build/models"),
    faceapi.nets.faceLandmark68Net.loadFromUri("build/models"),
    faceapi.nets.ssdMobilenetv1.loadFromUri("build/models")
]);

var container = document.getElementById('detection-object');
var detection_object = document.getElementById('object-to-detect');

var usersPhotosFolder = '/build/img/photos/';

document.getElementById('btn-start').addEventListener('click', async function() {
    const labeledFaceDescriptors = await loadLabeledImages();
    const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.6)
    let canvas;
    canvas = faceapi.createCanvasFromMedia(detection_object);
    container.append(canvas);
    const displaySize = { width: detection_object.width, height: detection_object.height };
    faceapi.matchDimensions(canvas, displaySize);
    const detections = await faceapi.detectAllFaces(detection_object).withFaceLandmarks().withFaceDescriptors();
    const resizedDetections = faceapi.resizeResults(detections, displaySize);
    let context = canvas.getContext('2d');
    context.clearRect(0, 0, canvas.width, canvas.height);
    const results = resizedDetections.map(d => faceMatcher.findBestMatch(d.descriptor));
    results.forEach((result, i) => {
        let nomComplet;
        utilisateurs.forEach(element => {
            if (element.cin === result._label) {
                nomComplet = element.nom + ' ' + element.prenom;
            }
        });
        const box = resizedDetections[i].detection.box;
        const drawBox = new faceapi.draw.DrawBox(box, { label: nomComplet.toString(), boxColor: '#56baed', lineWidth: '2' });
        drawBox.draw(canvas);
        if (result._label != "unknown") {
            showResultBox(result._label, nomComplet);
        }

    });
});

function loadLabeledImages() {
    return Promise.all(
        usernames.map(async label => {
            const descriptions = []
            for (let i = 1; i <= 3; i++) {
                const img = document.createElement("img");
                img.src = usersPhotosFolder + label + "/" + i + ".jpg";
                console.log(img);
                const detections = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor();
                console.log(detections);
                descriptions.push(detections.descriptor);
            }

            return new faceapi.LabeledFaceDescriptors(label, descriptions);
        })
    )
}

function showResultBox(cin, nomComplet) {
    var colonne = document.createElement('div');
    colonne.className = 'col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12';
    colonne.id = 'user-box';

    var card = document.createElement('div');
    card.className = 'card';

    var card_img = document.createElement('img');
    card_img.className = 'card-img-top img-fluid';
    card_img.src = usersPhotosFolder + cin + "/1.jpg";

    var card_body = document.createElement('div');
    card_body.className = 'card-body';

    var card_title = document.createElement('h3');
    card_title.className = 'card-title';
    card_title.textContent = nomComplet;

    var button = document.createElement('button');
    button.className = 'btn btn-primary';
    button.id = 'btn-show-more';
    button.textContent = 'Plus d\'information';

    card_body.appendChild(card_title);
    card_body.appendChild(button);

    card.appendChild(card_img);
    card.appendChild(card_body);

    colonne.appendChild(card);

    document.getElementById('users-info-section').appendChild(colonne);
}



document.getElementById('users-info-section').addEventListener('click', function(event) {

    let result = event.srcElement.parentElement.children[0].textContent;
    utilisateurs.forEach(element => {
        let nomComplet = element.nom + ' ' + element.prenom;
        if (nomComplet === result) {
            custumizeWrapper(element);
        }
    });

    document.getElementById('main').style.display = 'none';
    document.getElementById('wrapper-show-more-container').style.display = 'block';
});


function custumizeWrapper(utilisateur) {
    document.getElementById('wrapper-img').src = usersPhotosFolder + utilisateur.cin + "/1.jpg"

    let elements = document.getElementsByClassName("wrapper-body-element");

    elements[0].firstElementChild.textContent = utilisateur.cin;
    elements[1].firstElementChild.textContent = utilisateur.nom;
    elements[2].firstElementChild.textContent = utilisateur.prenom;
    elements[3].firstElementChild.textContent = utilisateur.age;
    elements[4].firstElementChild.textContent = utilisateur.adresse;

}

document.getElementById('close-wrapper').addEventListener('click', function(event) {
    document.getElementById('main').style.display = 'block';
    document.getElementById('wrapper-show-more-container').style.display = 'none';
});


$('#open-file-dialog').click(function() {
    $('#imgUpload').trigger('click');
});

$('input[type="file"]').change(function(event) {
    readURL(event.target);
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.readAsDataURL(input.files[0]);
        reader.onload = function(e) {
            $('#object-to-detect').attr('src', e.target.result);
        }

    }
}

var test = [];