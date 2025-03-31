
<div class="w-full relative bg-white rounded-3xl">
<div class="w-full flex justify-center items-center">
<div class="w-full flex justify-center items-center rounded-3xl">
  <!-- Close Button -->
  <div id="modal-container" class="w-full flex flex-col justify-start items-center relative bg-white nunito- p-2 rounded-3xl bg-gradient-to-r from-[#184E77] to-[#52B69A]">
    <div class="w-full flex flex-col justify-start items-center bg-white p-8 rounded-3xl space-y-8">
      <div class="flex flex-col justify-center items-center space-y-4">
        <p class="text-5xl text-black font-bold">Incident</p>
        <p class="text-3xl text-[#00000080]">Edit details of the incident record</p>
      </div>
      <div class="w-full mx-auto p-6 ">
      <form action="{{ route('incident.update', $incident->id) }}" method="POST" class="w-full mx-auto p-6 "  enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-2 gap-4">
   

          <div>
            <label for="employee_id" class="block text-xl text-black font-bold">Employee ID:</label>
            <input
              type="number"
              id="employee_id"
              name="employee_id"
              placeholder="Enter employee id"
              value="{{ old('employee_id', $incident->employee_id) }}"

              class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            />
          </div>

          <div>
            <label for="incident_type" class="block text-xl text-black font-bold">Incident Type</label>
            <input
              type="text"
              id="incident_type"
              name="incident_type"
              placeholder="Enter incident type"
              value="{{ old('incident_type', $incident->incident_type) }}"

              class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            />
          </div>

          <div>
              <label for="incident_date" class="block text-xl text-black font-bold">Incident Date:</label>
              <input
                  type="date"
                  id="incident_date"
                  name="incident_date"
                  placeholder="DD:MM:YYYY"
                  value="{{ old('incident_date', $incident->incident_date) }}"

                  class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              />
          </div>

          <div>
            <label for="resolution_status" class="block text-xl text-black font-bold">Resolution Status:</label>
            <input
              type="text"
              id="resolution_status"
              name="resolution_status"
              placeholder="Enter the status"
              value="{{ old('resolution_status', $incident->resolution_status) }}"

              class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            />
          </div>
        
          <div>
            <label for="doc-files" class="flex items-center justify-center px-4 py-2 bg-[#184E77] border-2 border-[#52B69A80] text-white rounded-md cursor-pointer hover:bg-blue-600 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"> 
                <span class="iconify" data-icon="ic:sharp-upload" style="width: 16px; height: 16px;"></span>
                <span class="ml-2">Upload Files</span>
            </label>
    
            <input type="file" id="doc-files" accept="application/pdf" class="hidden" multiple />
            <input type="file" name="supporting_documents[]" id="hidden-files" class="hidden" multiple />
            <input type="hidden" id="existing-files-data" value='{{ $incident->supporting_documents }}'>
            <input type="hidden" name="existing_files" id="existing-files">

            <div id="file-list" class="text-black text-sm w-full bg-[#D9D9D980] flex flex-col justify-end items-end rounded-b-xl pr-4 pt-4">
              <p>Attached Files:</p>
              <ul id="file-list-items" class="w-full px-4">
                  @php
                      $documents = json_decode($incident->supporting_documents, true) ?: [];

                  @endphp

                  @if (empty($documents))
                      <li class="text-gray-500">No documents attached.</li>
                  @else
                      @foreach ($documents as $document)
                          <li>
                              <span class="text-2xl">
                                  <i class="ri-file-pdf-2-fill"></i>
                              </span>
                              <a href="{{ asset('storage/' . $document) }}" target="_blank" class="text-blue-500 underline">
                                  {{ basename($document) }}
                              </a>
                              <span onclick="removeDocument('{{ $document }}')" class="text-red-500 cursor-pointer ml-2">✖</span>
                          </li>
                      @endforeach
                  @endif
              </ul>
          </div>

          </div>


        <div>
    <label for="description" class="block text-xl text-black font-bold">Description:</label>
    <textarea
        id="description"
        name="description"
        placeholder="Enter the description"
        rows="3"
        class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
   > {{ $incident->description }}</textarea>
  </textarea>
</div>


        <!-- Submit Button -->
        <div class="mt-6 col-span-2 flex justify-center">
      <button
        type="submit"
        class="w-1/2 bg-gradient-to-r from-[#184E77] to-[#52B69A] text-white font-medium px-6 py-2 rounded-md hover:from-blue-600 hover:to-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
      >
        Save Record
      </button>
    </div>
</form> 
      </div>
    </div>
  </div>
</div>
</div>
</div>

<style>
  input::placeholder,
  textarea::placeholder {
    color: #0000008C;
    opacity: 1;
  }
</style>
<script>
function toggleGradientText() {
    const textElement = document.getElementById('payrollText');
    if (textElement.classList.contains('text-black')) {
      // Apply gradient
      textElement.classList.remove('text-black');
      textElement.classList.add('bg-gradient-to-r', 'from-[#184E77]', 'to-[#52B69A]', 'text-transparent', 'bg-clip-text');
    } else {
      // Revert to black
      textElement.classList.add('text-black');
      textElement.classList.remove('bg-gradient-to-r', 'from-[#184E77]', 'to-[#52B69A]', 'text-transparent', 'bg-clip-text');
    }
  }

  function toggleMenu(menuId) {
        const menu = document.getElementById(menuId);
        menu.classList.toggle('hidden');
    }

    const textElements = document.querySelectorAll('span.text-xl');

    textElements.forEach((element) => {
        element.addEventListener('click', function () {
            // Reset all text elements to black
            textElements.forEach((el) => {
                el.classList.remove('bg-gradient-to-r', 'from-[#184E77]', 'to-[#52B69A]', 'text-transparent', 'bg-clip-text');
                el.classList.add('text-black');
            });

            // Apply gradient to the clicked element
            this.classList.remove('text-black');
            this.classList.add('bg-gradient-to-r', 'from-[#184E77]', 'to-[#52B69A]', 'text-transparent', 'bg-clip-text');
        });
    });


    document.querySelector('form').addEventListener('submit', async function (e) {
    e.preventDefault(); // Prevent default form submission

    const formData = new FormData(this);

    // Collect files
    const files = formData.getAll('attachments[]');
    const fileArray = await Promise.all(
        files.map(file => {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = () => resolve({ name: file.name, content: reader.result });
                reader.onerror = reject;
                reader.readAsDataURL(file); // Encode file as base64
            });
        })
    );

    // Add files to the JSON data
    const jsonData = {};
    formData.forEach((value, key) => {
        if (key !== 'attachments[]') {
            jsonData[key] = value;
        }
    });
    jsonData.attachments = fileArray;

    // Send the JSON data to the server
    fetch('{{ route("leave.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(jsonData)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        alert('Leave record added successfully');
        console.log(data);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('There was an error adding the leave record');
    });
});

function loadDescription(description) {
      const descriptionTextarea = document.getElementById('description');
      if (descriptionTextarea) {
          descriptionTextarea.value = description;
      }
  }

  // Example: Load the description dynamically
  const dynamicDescription = "{{ $incident->description }}";
  loadDescription(dynamicDescription);


  document.getElementById('doc-files').addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    const hiddenInput = document.getElementById('hidden-files');
    const dataTransfer = new DataTransfer();
    
    // Add new files to hidden input
    files.forEach(file => {
        dataTransfer.items.add(file);
    });
    
    // Set the files to the hidden input
    hiddenInput.files = dataTransfer.files;
    
    // Add new files to the list
    const fileList = document.getElementById('file-list-items');
    files.forEach(file => {
        const li = document.createElement('li');
        li.className = 'flex items-center space-x-2 mb-2';
        li.innerHTML = `
            <span class="text-2xl">
                <i class="ri-file-pdf-2-fill"></i>
            </span>
            <span class="text-blue-500 flex-grow">${file.name}</span>
            <span onclick="removeDocument(this)" class="text-red-500 cursor-pointer ml-2">✖</span>
        `;
        fileList.appendChild(li);
    });
});

  


</script>

