@component('mail::message')
<body>
<table style="max-width: 600px; padding: 10px; margin: 0 auto; border-collapse: collapse;">
    <tr>
        <td style="text-align: center;">
            <p style="font-family: Poppins Sans-serif,sans-serif; color: #96D293; font-weight: bold; font-size: 55px; margin: 5px;">
                ¡Estás a un paso!</p>
        </td>
    </tr>
    <tr>
        <td style="text-align: center; ">
            <p style="font-family: Poppins Sans-serif,sans-serif; font-size: 40px; margin: 0;">Para poder
                retirar <span style="font-weight: 600;">{{$params['offer']}}</span> dictá
                el siguiente código en el comercio</p>
        </td>
    </tr>
    <tr>
        <td style="text-align: center; height:280px; background-image: url('https://teloregalo.com.ar/assets/emails/ticketConfirmacion.png'); background-repeat: no-repeat;">
                      <p style="font-family: Poppins Sans-serif,sans-serif; font-size: 40px; text-align: center; position:relative; top:20px;">{{$params['otp']}}</p>
        </td>
    </tr>
    <tr>
        <td style="text-align: center;">
            <p style="font-family: Poppins Sans-serif,sans-serif; font-size: 19px; line-height: 36px; text-align: justify;
                color: #263238; padding: 10px;">¡Avisale a tus
                amigos y comerciantes
                de tu barrio para ayudarnos entre todos! <br>
                No olvides calificar tu experiencia con el comercio en nuestra página.</p>
        </td>
    </tr>
    <tr>
        <td style="text-align: center;">
            <button style="color:#2E2C2C; background-color: #96D293; border: 0.1rem solid;
        border-color: #96D293; border-radius: 1rem; padding: 0.3rem 1rem; font-weight: 400;
        text-align: center; font-family: Poppins Sans-serif,sans-serif; font-size: 18px;">Ver
                código en la
                página
            </button>
        </td>
    </tr>
    <tr style="margin-bottom: 100px">
        <td style="text-align: center;">
            <p style="font-family: Poppins Sans-serif,sans-serif; font-size: 23px;">El equipo de <b>teloregalo</b>
                <img style="max-width: 50px; height: auto; margin-left: 15px; margin-top: 15px;" alt="Logo Te lo regalo" src="https://teloregalo.com.ar/assets/emails/logo.png"/>
            </p>
        </td>
    </tr>
</table>
</body>
@endcomponent
