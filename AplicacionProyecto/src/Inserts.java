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
	
	public int insertarDesbloqueo(JTextField[] txtEditables){

		try {
			int longT = txtEditables.length;
			cst = conexion.prepareCall("{call InsertarDesbloqueo(?,?,?,?,?,?,?,?)}");
			for (int i = 1; i < longT; i++) {
				cst.setString(i, txtEditables[i].getText());
			}
			cst.registerOutParameter(longT, java.sql.Types.INTEGER);
			cst.execute();
			return cst.getInt(longT);
			
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
	
	public int insertarPuntuacion(JTextField[] txtEditables){

		try {
			int longT = txtEditables.length;
			cst = conexion.prepareCall("{call InsertarPuntuacion(?,?,?,?)}");
			for (int i = 2; i < longT; i++) {
				cst.setString(i-1, txtEditables[i].getText());
			}
			cst.registerOutParameter(longT-1, java.sql.Types.INTEGER);
			cst.execute();
			return cst.getInt(longT-1);
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return 2;
	}
	
	public int insertarJuego(JTextField[] txtEditables){

		try {
			int longT = txtEditables.length;
			cst = conexion.prepareCall("{call InsertarJuego(?,?,?)}");
			for (int i = 1; i < longT-1; i++) {
				cst.setString(i, txtEditables[i].getText());
			}
			cst.registerOutParameter(longT-1, java.sql.Types.INTEGER);
			cst.execute();
			return cst.getInt(longT-1);
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return 2;
	}
	
	public int insertarAjustes(JTextField[] txtEditables){

		try {
			cst = conexion.prepareCall("{call InsertarAjustes(?,?)}");
			cst.setString(1, txtEditables[3].getText());
			cst.registerOutParameter(2, java.sql.Types.INTEGER);
			cst.execute();
			return cst.getInt(2);
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return 2;
	}
	
}
