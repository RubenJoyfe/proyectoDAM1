import java.sql.CallableStatement;
import java.sql.Connection;
import java.sql.SQLException;
import java.util.Iterator;

import javax.swing.JTextField;

public class Inserts {
	
	private CallableStatement cst;
	private Connection conexion;
	
	public Inserts(Connection cnx) {
		conexion=cnx;
	}
	
	
	public int insertarUsuario(JTextField[] txtEditables){

		try {
			cst = conexion.prepareCall("{call InsertarUsuario(?,?,?,?,?,?,?)}");
			for (int i = 1; i < 7; i++) {
				cst.setString(i, txtEditables[i].getText());
			}
			cst.registerOutParameter(7, java.sql.Types.INTEGER);
			cst.execute();
			return cst.getInt(7);
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return 2;
	}
	
	public int insertarUsuarioDesbloqueo(JTextField[] txtEditables){

		try {
			int longT = txtEditables.length;
			cst = conexion.prepareCall("{call InsertarUsuario_desbloqueo(?,?,?)}");
			for (int i = 2; i < longT; i++) {
				cst.setString(i-1, txtEditables[i].getText());
			}
			cst.registerOutParameter(3, java.sql.Types.INTEGER);
			cst.execute();
			return cst.getInt(3);
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return 2;
	}
	
	public int insertarUsuarioJuego(JTextField[] txtEditables){

		try {
			int longT = txtEditables.length;
			cst = conexion.prepareCall("{call InsertarUsuario_Juego(?,?,?)}");
			for (int i = 3; i < longT; i++) {
				cst.setString(i-2, txtEditables[i].getText());
			}
			cst.registerOutParameter(3, java.sql.Types.INTEGER);
			cst.execute();
			return cst.getInt(3);
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return 2;
	}
}
