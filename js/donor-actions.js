document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.open-modal').forEach(button => {
        button.addEventListener('click', function () {
            const donorId = this.getAttribute('data-id');
            const action = this.getAttribute('data-action');
            const name = this.getAttribute('data-name');

            document.getElementById('modalDonorId').value = donorId;
            document.getElementById('modalAction').value = action;

            const modalMessage = action === 'approve'
                ? `Are you sure you want to <strong>approve</strong> donor <strong>${name}</strong>?`
                : `Are you sure you want to <strong class="text-danger">reject</strong> donor <strong>${name}</strong>?`;

            document.getElementById('modalMessage').innerHTML = modalMessage;

            const submitBtn = document.getElementById('modalSubmitBtn');
            submitBtn.className = action === 'approve' ? 'btn btn-success' : 'btn btn-danger';
            submitBtn.textContent = action === 'approve' ? 'Yes, Approve' : 'Yes, Reject';
        });
    });
});
