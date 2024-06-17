
		document.addEventListener('DOMContentLoaded', async () => {
async function fetchAndPopulateFiles() {
const response = await fetch('/api/files/explorer');
const data = await response.json();
const fileExplorer = document.getElementById('externalFile');
console.log(data);

data.files.forEach(file => {
const fileElement = document.createElement('div');
fileElement.textContent = file.name;
fileElement.dataset.fileName = file.name;
fileElement.dataset.filePath = file.path;
fileElement.addEventListener('click', () => selectExternalFile(fileElement));
fileExplorer.appendChild(fileElement);
});
}

function selectExternalFile(fileElement) {
document.querySelectorAll('#externalFile div').forEach(div => div.classList.remove('selected'));
fileElement.classList.add('selected');
document.getElementById('localFileInput').value = ''; // Clear local file input selection
}

function handleFileSelection() {
const selectedExternalFile = document.querySelector('#externalFile div.selected');
const selectedLocalFile = document.getElementById('localFileInput').files[0];

if (selectedExternalFile) {
document.getElementById('selectedFile').value = selectedExternalFile.dataset.fileName;
document.getElementById('selectedFilePath').value = selectedExternalFile.dataset.filePath;
} else if (selectedLocalFile) {
document.getElementById('selectedFile').value = selectedLocalFile.name;
document.getElementById('selectedFilePath').value = '';
} else {
alert('Please select a file.');
return;
}

const modal = document.getElementById('fileModal');
const bootstrapModal = bootstrap.Modal.getInstance(modal);
bootstrapModal.hide();
}

async function handleMmsSending() {
const to = document.getElementById('phoneNumber').value;
const body = document.getElementById('messageBody').value;
const fileInput = document.getElementById('localFileInput').files[0];
const selectedExternalFile = document.querySelector('#externalFile div.selected');
const selectedLocalFile = fileInput ? fileInput : null;
const externalFileName = selectedExternalFile ? selectedExternalFile.dataset.fileName : null;
const externalFilePath = document.getElementById('selectedFilePath').value;

const selectedData = new FormData();
selectedData.append('to', to);
selectedData.append('body', body);

if (selectedLocalFile) {
selectedData.append('file', selectedLocalFile);
} else {
selectedData.append('fileName', externalFileName);
}

try {
const mmsResponse = await fetch('/api/notification/sendMms', {
method: 'POST',
body: selectedData
});

if (! mmsResponse.ok) {
throw new Error (`HTTP error! status: ${
mmsResponse.status
}`);
}

const responseJson = await mmsResponse.json();
console.log('MMS sent successfully:', responseJson);

// If external file, send a request to delete the file
if (externalFilePath) {
await fetch('/api/files/delete', {
method: 'POST',
headers: {
'Content-Type': 'application/json'
},
body: JSON.stringify(
{path: externalFilePath}
)
});
}
} catch (error) {
console.log('Error sending MMS:', error);
}
}

await fetchAndPopulateFiles();
document.getElementById('selectFileBtn').addEventListener('click', handleFileSelection);
document.getElementById('sendMmsBtn').addEventListener('click', handleMmsSending);
});


	
