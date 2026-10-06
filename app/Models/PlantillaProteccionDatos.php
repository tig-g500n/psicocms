<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantillaProteccionDatos extends Model
{
    protected $table = 'plantilla_proteccion_datos';

    protected $guarded = [];

    public static function singleton(): self
    {
        return static::firstOrCreate([], ['contenido' => static::porDefecto()]);
    }

    public static function porDefecto(): string
    {
        return <<<'HTML'
<h2 style="text-align:center;">Documento de consentimiento y protección de datos</h2>
<p><strong>Profesional:</strong> {{psicologa_nombre}} — Nº de colegiado/a: {{psicologa_colegiado}}</p>
<p><strong>Fecha:</strong> {{fecha}}</p>
<hr>
<h3>Datos del paciente</h3>
<ul>
    <li><strong>Nombre y apellidos:</strong> {{paciente_nombre}}</li>
    <li><strong>Teléfono:</strong> {{paciente_telefono}}</li>
    <li><strong>Email:</strong> {{paciente_email}}</li>
    <li><strong>Fecha de nacimiento:</strong> {{paciente_fecha_nacimiento}}</li>
    <li><strong>Dirección:</strong> {{paciente_direccion}}</li>
</ul>
<h3>Información básica sobre protección de datos</h3>
<p>De conformidad con el Reglamento (UE) 2016/679 (RGPD) y la Ley Orgánica 3/2018 (LOPDGDD), le informamos de que sus datos personales serán tratados por la profesional arriba indicada con la finalidad de gestionar su asistencia psicológica, las citas y el seguimiento de su proceso terapéutico.</p>
<p>La base legal del tratamiento es su consentimiento y la ejecución de la relación asistencial. Sus datos no serán cedidos a terceros salvo obligación legal, y se conservarán durante el tiempo necesario para cumplir con las obligaciones legales aplicables.</p>
<p>Puede ejercer sus derechos de acceso, rectificación, supresión, oposición, limitación y portabilidad dirigiéndose a la profesional a través de los datos de contacto facilitados.</p>
<h3>Consentimiento</h3>
<p>Yo, <strong>{{paciente_nombre}}</strong>, declaro haber sido informado/a y presto mi consentimiento para el tratamiento de mis datos personales con las finalidades descritas.</p>
<br><br>
<table style="width:100%;">
    <tr>
        <td style="width:50%;">Firma del paciente:<br><br>________________________</td>
        <td style="width:50%;">Firma de la profesional:<br><br>________________________</td>
    </tr>
</table>
HTML;
    }
}
