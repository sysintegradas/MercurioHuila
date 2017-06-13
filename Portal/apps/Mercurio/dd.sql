INSERT INTO aportes004(fecharadicacion, identificacion, idtipodocumento, idtiporadicacion, idtipopresentacion, horainicio, horafinal, tiempo, notas,
        idtipoinformacion, idtipodocumentoafiliado, numero, nit, folios, idtipocertificado, idbeneficiario, observaciones, asignado, fechaasignacion, digitalizada, fechadigitalizacion, procesado, fechaproceso, 
        flag, tempo1, fechasistema, usuario, usuarioasignado, anulada, usuarioanula, idtipoformulario, devuelto, motivodevolucion, idagencia, idtipodocben, numerobeneficiario, afiliacionmultiple, cierre, recibeventanilla, recibegrabacion, fecharecibegraba, usuarioaudita, fechaaudita, idtipomovilizacion, fecha_presentacion, habeas_data) 
    VALUES('"+ fecsis +"', '"+ cedtra +"', "+ coddoc +", '28', 4621, '10:15:59', '10:15:59', '0', 'RADICACION POR CONSULTA EN LINEA (" + usuario + ")',
            '' , 'xxxx', 'xxxx', 'xxxx','"+ folios +"', 0, 0, '', 'S', '', '', '', 'S', '"+ fecsis +"',
            '', '', '"+ fecsis +"', 'Consulta', '', '', '', 0, '', 0, '"+agencia+"', 0, '', N, N,'', '"+ususesion+"', '"+fecsis+"', '', '', 0, '', 1) 

