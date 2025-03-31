
<div class="w-full relative bg-white rounded-3xl">
<div class="w-full flex justify-center items-center">
<div class="w-full flex justify-center items-center rounded-3xl">
  <!-- Close Button -->
  <div id="modal-container" class="w-full flex flex-col justify-start items-center relative bg-white nunito- p-2 rounded-3xl bg-gradient-to-r from-[#184E77] to-[#52B69A]">
    <div class="w-full flex flex-col justify-start items-center bg-white p-8 rounded-3xl space-y-8">
      <div class="flex flex-col justify-center items-center space-y-4">
        <p class="text-5xl text-black font-bold">Expense</p>
        <p class="text-3xl text-[#00000080]">Add details of the  record</p>
      </div>
      <div class="w-full mx-auto p-6 ">
      <form action="{{ route('expenses.store') }}" method="POST" class="w-full mx-auto p-6 "  enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-2 gap-4">

          <div>
            <label for="employment_ID" class="block text-xl text-black font-bold">Employee ID:</label>
            <input
              type="text"
              id="employee_id"
              name="employee_id"
              placeholder="Enter employee id"

              class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            />
          </div>

          <div>
            <label for="expense_type" class="block text-xl text-black font-bold">Expense Category</label>
            <input
              type="text"
              id="expense_type"
              name="expense_type"
              placeholder="Enter expense type"

              class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            />
          </div>

          <div>
              <label for="expense_date" class="block text-xl text-black font-bold">Date:</label>
              <input
                  type="date"
                  id="expense_date"
                  name="expense_date"
                  placeholder="DD:MM:YYYY"

                  class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              />
          </div>

          <div>
            <label for="amount" class="block text-xl text-black font-bold">Amount:</label>
            <input
              type="number"
              id="amount"
              name="amount"
              placeholder="Enter amount"

              class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            />
          </div>



          
          <div>
            <label for="expense_status" class="block text-xl text-black font-bold">Approval Status :</label>
            <select
              id="expense_status"
              name="expense_status"
              class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] rounded-md shadow-sm focus:bg-gradient-to-r from-[#184E77] to-[#52B69A] text-xl text-black focus:border-blue-500 font-bold text-[#0000008C]">
              <option value="approved">Approved</option>
              <option value="pending">Pending</option>
              <option value="rejected">Rejected</option>
            </select>
          </div>

          
          <div>
            <label for="approved_by" class="block text-xl text-black font-bold">Approved by:</label>
            <input
              type="text"
              id="approved_by"
              name="approved_by"
              placeholder="Approved by"

              class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            />
          </div>

          
          <div>
            <label for="doc-files" class="flex items-center justify-center px-4 py-2 bg-[#184E77] border-2 border-[#52B69A80] text-white rounded-md cursor-pointer hover:bg-blue-600 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"> 
                <span class="iconify" data-icon="ic:sharp-upload" style="width: 16px; height: 16px;"></span>
                <span class="ml-2">Upload Files</span>
            </label>
            <!-- Visible File Input -->
            <input type="file" id="doc-files" accept="application/pdf" class="hidden" multiple />
        
            <input type="file" name="supporting_documents[]" id="hidden-files" class="hidden" multiple />
            <div id="file-list" class="text-black text-sm w-full bg-[#D9D9D980] flex flex-col justify-end items-end rounded-b-xl pr-4 pt-4">
            <p>Attached Files:</p>
            <ul id="file-list-items"></ul>
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
    ></textarea>
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
    fetch('{{ route("incident.store") }}', {
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
        alert('Incident record added successfully');
        console.log(data);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('There was an error adding the incident record');
    });
});

</script>

