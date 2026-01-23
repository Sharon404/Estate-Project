$(document).ready(function(){
    
    // Function to convert date to m/d/Y format
    function formatDateToMDY(dateString) {
        if(!dateString) return null;
        
        // Trim whitespace
        dateString = dateString.trim();
        console.log('formatDateToMDY input:', dateString);
        
        // Try parsing "Jan 23, 2026" or "January 23, 2026" format
        var dateParts = dateString.match(/^([a-zA-Z]+)\s+(\d{1,2}),?\s+(\d{4})$/);
        if(dateParts) {
            var monthName = dateParts[1];
            var day = dateParts[2];
            var year = dateParts[3];
            
            var months = {
                'January': 1, 'February': 2, 'March': 3, 'April': 4, 'May': 5, 'June': 6,
                'July': 7, 'August': 8, 'September': 9, 'October': 10, 'November': 11, 'December': 12,
                'Jan': 1, 'Feb': 2, 'Mar': 3, 'Apr': 4, 'May': 5, 'Jun': 6,
                'Jul': 7, 'Aug': 8, 'Sep': 9, 'Oct': 10, 'Nov': 11, 'Dec': 12
            };
            
            if(months[monthName]) {
                var month = months[monthName];
                var formatted = month + '/' + day + '/' + year;
                console.log('Formatted to:', formatted);
                return formatted;
            }
        }
        
        // Try parsing "1/23/2026" or "01/23/2026" format
        var slashParts = dateString.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
        if(slashParts) {
            var month = parseInt(slashParts[1]);
            var day = slashParts[2];
            var year = slashParts[3];
            var formatted = month + '/' + day + '/' + year;
            console.log('Formatted to:', formatted);
            return formatted;
        }
        
        // Try parsing "23/1/2026" or European format "dd/mm/yyyy"
        var euParts = dateString.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
        if(euParts) {
            // Could be dd/mm/yyyy - try to detect
            var first = parseInt(euParts[1]);
            var second = parseInt(euParts[2]);
            var year = euParts[3];
            
            // If first > 12, it's definitely day, so swap
            if(first > 12) {
                var formatted = second + '/' + first + '/' + year;
            } else {
                var formatted = first + '/' + second + '/' + year;
            }
            console.log('Formatted to:', formatted);
            return formatted;
        }
        
        // Try parsing with Date object as last resort
        try {
            var parsedDate = new Date(dateString);
            if(!isNaN(parsedDate.getTime())) {
                var month = parsedDate.getMonth() + 1; // getMonth() returns 0-11
                var day = parsedDate.getDate();
                var year = parsedDate.getFullYear();
                var formatted = month + '/' + day + '/' + year;
                console.log('Formatted to:', formatted);
                return formatted;
            }
        } catch(e) {
            console.log('Date parse error:', e);
        }
        
        console.log('Failed to parse date:', dateString);
        return null;
    }
    
    $('#send_message').click(function(e){
        
        //Stop form submission & check the validation
        e.preventDefault();
        
        // Variable declaration
        var error = false;
        var name = $('#name').val();
        var email = $('#email').val();
        var phone = $('#phone').val();
        var checkin = $('#date-picker').val();
        var adult = $('input[name="adult"]').val();
        var children = $('input[name="children"]').val();
        var room_count = $('#room-count').val();
        var room_type = $('.room-type').val();
        var message = $('#message').val();
        
        $('#name,#email,#phone,#message').click(function(){
            $(this).removeClass("error_input");
        });
        
        // Form field validation
        if(name.length == 0){
            var error = true;
            $('#name').addClass("error_input");
        }else{
            $('#name').removeClass("error_input");
        }
        if(email.length == 0 || email.indexOf('@') == '-1'){
            var error = true;
            $('#email').addClass("error_input");
        }else{
            $('#email').removeClass("error_input");
        }
        if(phone.length == 0){
            var error = true;
            $('#phone').addClass("error_input");
        }else{
            $('#phone').removeClass("error_input");
        }
        
        // Check if dates are selected
        if(!checkin || checkin.length == 0){
            error = true;
            alert('Please select check-in and check-out dates');
        }
        
        // If there is no validation error, next to process the booking
        if(error == false){
           // Disable submit button
            $('#send_message').attr({'disabled' : 'true', 'value' : 'Sending...' });
            
            // Parse date range from the date picker
            var dateRange = $('#date-picker').val();
            var dates = dateRange.split(' - ');
            
            // Format dates to m/d/Y format required by backend
            var checkInDate = formatDateToMDY(dates[0]);
            var checkOutDate = formatDateToMDY(dates[1] || dates[0]);
            
            console.log('Raw dateRange:', dateRange);
            console.log('Parsed dates:', dates);
            console.log('Formatted checkInDate:', checkInDate);
            console.log('Formatted checkOutDate:', checkOutDate);
            
            if(!checkInDate || !checkOutDate){
                error = true;
                alert('Invalid date format. Please select valid dates.\n\nDates received: ' + dateRange);
                $('#send_message').removeAttr('disabled').attr('value', 'Submit Form');
                return false;
            }
            
            /* Post Ajax function to submit the booking form */
            $.ajax({
                type: 'POST',
                url: '/booking/submit',
                dataType: 'json',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    name: name,
                    email: email,
                    phone: phone,
                    message: message,
                    checkin: checkInDate,
                    checkout: checkOutDate,
                    adult: adult,
                    children: children,
                    room_count: room_count,
                    room_type: room_type
                },
                success: function(response){
                    // Show success message
                    $('#booking_form_wrap').fadeOut(500);
                    $('#success_message').fadeIn(500);
                    
                    // Redirect to payment page after 2 seconds
                    setTimeout(function(){
                        window.location.href = response.redirect_url || '/';
                    }, 2000);
                },
                error: function(xhr, status, error){
                    console.error('Booking error:', xhr.responseText);
                    
                    // Show error message
                    var errorMessage = 'An error occurred while creating your booking.';
                    
                    if(xhr.responseJSON && xhr.responseJSON.message){
                        errorMessage = xhr.responseJSON.message;
                    } else if(xhr.status === 422){
                        // Validation errors
                        var errors = xhr.responseJSON.errors;
                        if(errors){
                            errorMessage = Object.values(errors).flat().join('\n');
                        }
                    }
                    
                    alert(errorMessage);
                    
                    // Enable the submit button again
                    $('#send_message').removeAttr('disabled').attr('value', 'Submit Form');
                }
            });
        }
    });    
});