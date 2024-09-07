document.getElementById('uploadForm').addEventListener('submit', function(event) {
  event.preventDefault();

  const formData = new FormData(this);

  fetch('upload.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(result => {
    alert(result.message);
  })
  .catch(error => {
    alert('Error: ' + error.message);
  });
});
