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
}
