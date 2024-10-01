<!--
 * Created by PhpStorm.
 * User: Amwar
 * Date: 14/03/2017
 * Time: 05:03 PM
-->

<p>Estimado: {{$messages->customer->nameComplete()}}</p>
<p>Muchas gracias por confiar en nuestros servicios.</p>
<p>Hemos recibido su {{$messages->equipment}} el día de hoy, para revisión con las siguientes características: </p>
<ul>
    <li>Boleta #: {{$messages->numeration}}</li>
    <li>Equipo: {{$messages->equipment}}</li>
    <li>Marca: {{$messages->brand->name}}</li>
    <li>Modelo: {{$messages->modelWorkshop->name}}</li>
    <li>Color: {{$messages->color}}</li>
    <li>Serie: {{$messages->serie}}</li>
    <li>Señas físicas: {{$messages->physicalSigns}}</li>
    @if($messages->charger =='si')
        <li>Cargador serie#: {{$messages->chargerSeries}}</li>
    @endif
    <li>Pin, contraseña o patrón de desbloqueo: {{$messages->password}}</li>
</ul>
<p>Problema reportado: {{$messages->reportedProblems}}</p>
<p>Solicitudes adicionales: {{$messages->additionalRequests}}</p>
<p>Precio por revisión el cual no se cobrará en caso de efectuarse la reparación: ₡</p>
<p>Adelanto: ₡</p>
<p>Nota importante: No asumimos responsabilidad alguna por daños ocultos en el equipo, por uso o fábrica, los cuales no son visibles a la hora de recibir el equipo.</p>
<p>Yo {{$messages->customer->nameComplete()}} entiendo que todo trabajo técnico cuenta con un mes de garantía sobre defectos de la misma reparación, y no otro componente no trabajado.
    Luego de ser notificado por teléfono, SMS, WhatsApp o Correo electrónico, cuento con un mes de tiempo para retirar el equipo, luego de este tiempo,
    si quiero retirar el equipo, debo cancelar un monto por ₡20000 por cada mes de retraso, sin excepción, por concepto de bodegaje adicional al costo de la reparación,
    hasta un máximo de 5 meses, luego de este tiempo no podré hacer ningún tipo de reclamo ya que cedo la propiedad del mismo a SITE Technology.
</p>
<p>Firma del cliente: {{$messages->firm}}</p>
<p>Cualquier consulta o pregunta al respecto, por favor no dude en contactarnos al correo soporte@sitechcr.com con el número de boleta.</p>
<p>Saludos,</p>

