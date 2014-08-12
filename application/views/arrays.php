<html>
    <head>
        <script type="text/javascript" src="/application/js/jquery/jquery.js"></script>
        <script type="text/javascript" src="/application/js/jquery/jquery-plugins.js"></script>
        <script type="text/javascript" src="/application/js/jquery/ajax.js"></script>
        <script type="text/javascript" src="/application/js/brokers/TestBroker.js"></script>
        <script>
            $(function() {
                
                $('#addContactButton').click(function() {
                    var clone = $('#archetype').clone().increaseIndex('attr[contact][$i]');
                    $('#contactTable').append(clone);
                });
                
                $(document).on('click', '.removeContactButton', function() {
                    $(this).closest('tr').remove();
                });
                
                $('#send').click(function() {
                    
                    var data = $('#contactTable').find('input[type=text],select').serialize();
                    
                    TestBroker.post({
                        post: data,
                        success: function(res) {
                            console.log(res);
                        }
                    });
                });
            });
        </script>
    </head>
    <body>
        
        <input type="button" id="addContactButton" value="Add Contact" />
        <input type="button" id="send" value="Send Away" />
        
        <table id="contactTable">
            <tr id="archetype">
                <td>
                    <select name="attr[contact][0][type]">
                        <option value="tel">tel</option>
                        <option value="mob">mob</option>
                        <option value="www">www</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="attr[contact][0][value]" value="asd" />
                </td>
                <td>
                    <input type="button" class="removeContactButton" value="Remove" />
                </td>
            </tr>
        </table>
        
    </body>
</html>