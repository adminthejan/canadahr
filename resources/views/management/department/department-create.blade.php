<div id="modal-container" class="w-full flex flex-col justify-start items-center relative bg-white nunito- p-2 rounded-3xl bg-gradient-to-r from-[#184E77] to-[#52B69A]">
    <div class="w-full flex flex-col justify-start items-center bg-white p-8 rounded-3xl space-y-8">
      <div class="flex flex-col justify-center items-center space-y-4">
        <p class="text-5xl text-black font-bold">Department</p>
        <p class="text-3xl text-[#00000080]">Enter the Information about Department</p>
      </div>
      <div class="w-full mx-auto p-6">
      <form action="{{ route('department.store') }}" method="POST" class="w-full mx-auto p-6 "  >
      @csrf
      <div class="grid grid-cols-2 gap-4">
          <!-- Employee ID -->
          <div>
            <label for="department_id" class="block text-xl text-black font-bold">Department ID :</label>
            <input
              type="text"
              name = "department_id"
              id="department_id"
              placeholder="Enter Department ID"
              class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] rounded-md focus:ring-blue-500 focus:border-blue-500 text-[#0000008C] font-bold"
            />
          </div>
          <div>
            <label for="name" class="block text-xl text-black font-bold">Department Name:</label>
            <input
              type="text"
              id="name"
              name = "name"
              placeholder="Enter Department Name"
              class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] rounded-md focus:ring-blue-500 focus:border-blue-500 text-[#0000008C] font-bold"
            />
          </div>
          <div>
            <label for="branch" class="block text-xl text-black font-bold">Branch :</label>
            <input
              type="text"
              id="branch"
              name = "branch"
              placeholder="Enter branch"
              class="mt-1 block w-full px-3 py-2 border-2 border-[#1C1B1F80] rounded-md focus:ring-blue-500 focus:border-blue-500 font-bold text-[#0000008C]"
            />
          </div>
          

        <!-- Submit Button -->
        <div class="mt-6 text-center">
          <button
            type="submit"
            class="w-1/2 bg-gradient-to-r from-[#184E77] to-[#52B69A] text-white font-medium py-2 px-4 rounded-md hover:from-blue-600 hover:to-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
          Add Record
          </button>
        </div>
      </form>
        
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


</script>
