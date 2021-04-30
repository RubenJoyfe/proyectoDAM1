import java.awt.BorderLayout;
import java.awt.EventQueue;
import java.awt.GridLayout;
import java.sql.*;
import java.util.Iterator;

import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JPanel;
import javax.swing.JPasswordField;
import javax.swing.JScrollPane;
import javax.swing.JTable;
import javax.swing.JTextField;
import javax.swing.SwingConstants;
import javax.swing.border.EmptyBorder;
import javax.swing.event.ListSelectionEvent;
import javax.swing.event.ListSelectionListener;
import javax.swing.JMenuBar;
import javax.swing.JMenu;
import javax.swing.JMenuItem;
import javax.swing.JOptionPane;
import java.awt.event.ActionListener;
import java.awt.event.ActionEvent;
import java.awt.event.ItemListener;
import java.awt.event.ItemEvent;
import java.awt.Font;
import javax.swing.border.LineBorder;
import java.awt.Color;
import javax.swing.JButton;

public class Main extends JFrame {
	private static final long serialVersionUID = 4219163702005532108L;
	
	private Inserts insertacion;
	private Deletes eliminacion;
	private JPanel contentPane;
	private JMenuBar menuBar;
	private JMenu mnMostrar;
	private String nombre,  pass;
	private JMenuItem mntmTabla[];
	private Connection conexion;
	private Statement comando;
	private JPanel panel_tabla;
	
	private String titulos [];
	private Object celdas [][];
	private JScrollPane scroll = new JScrollPane();
	private JTable tabla;
	private JPanel panel_opciones;
	private JLabel lblNombreTabla;
	private JTextField[] txtEditables = new JTextField[0];
	private TextPrompt[] placeHolder = new TextPrompt[0];
	private JButton btnInsertar;


	/**
	 * Launch the application.
	 */
	public static void main(String[] args) {
		EventQueue.invokeLater(new Runnable() {
			public void run() {
				try {
					Main frame = new Main();
					frame.setVisible(true);
				} catch (Exception e) {
					e.printStackTrace();
				}
			}
		});
	}

	/**
	 * Create the frame.
	 */
	public Main() {
		setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		setBounds(100, 100, 848, 585);
		
		contentPane = new JPanel();
		contentPane.setBorder(new EmptyBorder(5, 5, 5, 5));
		setContentPane(contentPane);
		contentPane.setLayout(null);
		
		panel_tabla = new JPanel();
		panel_tabla.setBounds(10, 11, 619, 433);
		contentPane.add(panel_tabla);
		
		panel_opciones = new JPanel();
		panel_opciones.setBorder(new LineBorder(Color.GRAY));
		panel_opciones.setBounds(638, 11, 184, 502);
		contentPane.add(panel_opciones);
		panel_opciones.setLayout(null);
		
		lblNombreTabla = new JLabel("TABLA");
		lblNombreTabla.setFont(new Font("Tahoma", Font.PLAIN, 20));
		lblNombreTabla.setHorizontalAlignment(SwingConstants.CENTER);
		lblNombreTabla.setBounds(10, 11, 164, 51);
		panel_opciones.add(lblNombreTabla);
		
		btnInsertar = new JButton("Insertar");
		btnInsertar.addActionListener(new ActionListener() { // -------------------------> Listener Inserts
			public void actionPerformed(ActionEvent e) {
				inserts(); 
			}
		});
		btnInsertar.setBounds(10, 434, 164, 57);
		panel_opciones.add(btnInsertar);
		
		JButton btnModificar = new JButton("Modificar");
		btnModificar.setBounds(10, 366, 164, 57);
		panel_opciones.add(btnModificar);
		
		JButton btnEliminar = new JButton("Eliminar");
		btnEliminar.addActionListener(new ActionListener() { // ----------------------> Listener Eliminar
			public void actionPerformed(ActionEvent e) {
				String msg = "¿Está seguro que quiere eliminar este"
						+ " registro de la tabla " + lblNombreTabla.getText() + "?";
				int op = JOptionPane.showConfirmDialog(null, msg,
			            "Confirmacion", JOptionPane.YES_NO_OPTION, JOptionPane.WARNING_MESSAGE);
				System.out.println(op);
				if (op==0) {
					eliminar();
				}
				
			}
		});
		btnEliminar.setBounds(10, 298, 164, 57);
		panel_opciones.add(btnEliminar);
		
		do {
//			login();
			nombre = "Ruben";
			pass = "1234";
		}while (!conexionMysql("localhost", "h15af00", nombre, pass));
		startMenu();
		crearTabla("vacía");
		insertacion = new Inserts(conexion);
		eliminacion = new Deletes(conexion);
	}
	
	public void inserts() { // -------------------------------------------------------------------->inserts
		int resultado=1;
		String acTab=lblNombreTabla.getText().toLowerCase();
		
		switch (acTab) {
		case "usuario":
			resultado = insertacion.insertarUsuario(txtEditables);
			break;
			
		case "desbloqueo":
			resultado = insertacion.insertarDesbloqueo(txtEditables);
			break;
			
		case "usuario_desbloqueo":
			resultado = insertacion.insertarUsuarioDesbloqueo(txtEditables);
			break;
			
		case "usuario_juego":
			resultado = insertacion.insertarUsuarioJuego(txtEditables);
			break;
			
		case "puntuacion":
			resultado = insertacion.insertarPuntuacion(txtEditables);
			break;
			
		case "juego":
			resultado = insertacion.insertarJuego(txtEditables);		
			break;
			
		case "ajustes":
			resultado = insertacion.insertarAjustes(txtEditables);	
			break;
			
		default:
			JOptionPane.showMessageDialog(null, "Tabla no especificada", "Error", JOptionPane.ERROR_MESSAGE, null);
			break;
		}
		System.out.println(resultado);
//			System.out.println(insertacion.insertarUsuario(txtEditables));
		if (resultado==0) {
			crearTabla(acTab);
		}

	}
	
	
	public void eliminar(){
		int resultado=1;
		String acTab=lblNombreTabla.getText().toLowerCase();
		
		switch (acTab) {
		case "usuario":
			resultado = eliminacion.eliminarUsuario(txtEditables);
			break;
			
		case "desbloqueo":
			resultado = eliminacion.eliminarDesbloqueo(txtEditables);
			break;
			
		case "usuario_desbloqueo":
			resultado = eliminacion.eliminarUsuarioDesbloqueo(txtEditables);
			break;
			
		case "usuario_juego":
			resultado = eliminacion.eliminarUsuarioJuego(txtEditables);
			break;
			
		case "puntuacion":
			resultado = eliminacion.eliminarPuntuacion(txtEditables);
			break;
			
		case "juego":
			resultado = eliminacion.eliminarJuego(txtEditables);
			break;
			
		case "ajustes":
			resultado = eliminacion.eliminarAjustes(txtEditables);
			break;
			
		default:
			JOptionPane.showMessageDialog(null, "Tabla no especificada", "Error", JOptionPane.ERROR_MESSAGE, null);
			break;
		}
		System.out.println(resultado);
//			System.out.println(insertacion.insertarUsuario(txtEditables));
		if (resultado==0) {
			crearTabla(acTab);
		}

	}
	

	
	
	public void startMenu() {
		menuBar = new JMenuBar();
		setJMenuBar(menuBar);
		mnMostrar = new JMenu("Tablas");

		menuBar.add(mnMostrar);
		
		String sql = "SELECT table_name AS nombre FROM information_schema.tables"
				+ " WHERE table_schema = 'h15af00' ORDER BY nombre ASC";
		try {
			int rows=0;
			comando = conexion.createStatement();
			ResultSet rs = comando.executeQuery(sql);
			
			while (rs.next()) {rows++;} //contar el numero de filas que devuelve
			mntmTabla = new JMenuItem [rows];
			rs = comando.executeQuery(sql);
			
			rows=0;
			while (rs.next()) {
				String nombreOp = rs.getString("nombre");
				String nombreMay = rs.getString("nombre").substring(0, 1).toUpperCase()+ rs.getString("nombre").substring(1);
				mntmTabla[rows] = new JMenuItem(nombreMay);
				mnMostrar.add(mntmTabla[rows]);
				mntmTabla[rows].addActionListener(new ActionListener() {
					public void actionPerformed(ActionEvent e) {
						crearTabla(nombreOp);
						lblNombreTabla.setText(nombreMay);
					}
				});
				rows++;
			}
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
			
	
	}


	public void startEditables(String columnas []) {
		deleteEditables();
		placeHolder = new TextPrompt [columnas.length];
		txtEditables = new JTextField [columnas.length];
		for (int i = 0; i < columnas.length; i++) {
			txtEditables[i] = new JTextField();
			if (columnas[i].contains("id_") /*|| columnas[i].contains("fk_")*/) { //alomejor hay que quitar lo de fk_
				txtEditables[i].setEditable(false);
			} 
				
			txtEditables[i].setText("");
			txtEditables[i].setBounds(10+104*(i%6), 455+((i/6)*30), 100, 20);
			contentPane.add(txtEditables[i]);
			
			placeHolder[i] = new TextPrompt(columnas[i],txtEditables[i]);
			placeHolder[i].changeAlpha(0.8f);
			placeHolder[i].changeStyle(Font.PLAIN);
			
			
		}
	}
	
	public void deleteEditables() {
		for (int i = 0; i < txtEditables.length; i++) {
			remove(txtEditables[i]);
		}
		contentPane.repaint();
	}
	
	public boolean conexionMysql(String ip, String bbdd, String usuario, String pass) {
		try {
			conexion = DriverManager.getConnection(
			        "jdbc:mysql://" + ip + "/" + bbdd, usuario, pass);//jdbc:mysql://<ip>:<puerto>/<nombreDB>,"user","pass"
			return true;
		} catch (SQLException e) {
//			System.out.println("No se ha podido conectar a la base de datos. Error: " + e.getMessage());
			e.printStackTrace();
		}
		return false;
	}
	
	public void login() {
		JPanel panel = new JPanel(new BorderLayout(5, 5));
		

			JPanel label = new JPanel(new GridLayout(0, 1, 2, 2));
		    label.add(new JLabel("Usuario", SwingConstants.RIGHT));
		    label.add(new JLabel("Contraseña", SwingConstants.RIGHT));
		    panel.add(label, BorderLayout.WEST);
		    
		    JPanel controls = new JPanel(new GridLayout(0, 1, 2, 2));
		    JTextField username = new JTextField();
		    controls.add(username);
		    JPasswordField password = new JPasswordField();
		    controls.add(password);
		    panel.add(controls, BorderLayout.CENTER);
		    
		    int op = JOptionPane.showConfirmDialog(this, panel, "login", JOptionPane.OK_CANCEL_OPTION);
		    if (op==2||op==-1) {
		    	System.exit(0);
			}
		    
		    nombre = username.getText();
		    pass = new String(password.getPassword());		   
	}
	
	public void crearTabla(String tb) {
		if (tb.equals("vacía")) {
			tabla = new JTable();
			panel_tabla.setLayout(null);
			scroll.setViewportView(tabla);
			scroll.setBounds(0, 0, 618, 502);
		    panel_tabla.add(scroll);
		} else {
			int nCol=0, nRow=0;
			ResultSet rs;
			String sql = "SELECT * FROM " + tb +";";
			String sql2= "SELECT COLUMN_NAME as nombreColumna FROM information_schema.COLUMNS WHERE "
					+ "TABLE_SCHEMA  LIKE 'h15af00' AND TABLE_NAME = '"+tb+"'";
			
			try {
				int cont=0;
				
				rs = comando.executeQuery(sql2);
				while (rs.next()) {nCol++;} //cuenta el numero de columnas
				titulos = new String [nCol]; //nCol asigna su tamano
		
				rs = comando.executeQuery(sql2);
				while (rs.next()) { 
					titulos[cont]=rs.getString("nombreColumna");
					cont++;
				}
				startEditables(titulos);
				ResultSet rs2 = comando.executeQuery(sql);
				while (rs2.next()) {nRow++;}
				celdas=new Object[nRow][nCol];
				cont=0;
				int cont2=0;
				rs2 = comando.executeQuery(sql);
				while (rs2.next()) {
					while (cont2<nCol) {
						celdas[cont][cont2]=rs2.getString(titulos[cont2]);
						cont2++;
					}
					cont++;
					cont2=0;
				}
				tabla=new JTable(celdas, titulos);
			} 
			catch (SQLException e) {
				e.printStackTrace();
			}
		}
		tabla.setName(tb);
		panel_tabla.setLayout(null);
		scroll.setViewportView(tabla);
		scroll.setBounds(0, 0, 619, 433);
	    panel_tabla.add(scroll);
	    tabla.setCellSelectionEnabled(true);
		tabla.getSelectionModel().addListSelectionListener(new ListSelectionListener(){
	        public void valueChanged(ListSelectionEvent event) {
	        	if(event.getValueIsAdjusting()) {
//	        		System.out.println("columna: " + tabla.getColumnName(tabla.getSelectedColumn()));
//	        		System.out.println("fila: "+tabla.getValueAt(tabla.getSelectedRow(), 0).toString());
//	        		System.out.println("Identifier: " + tabla.getColumn("nick").getIdentifier());
//	        		System.out.println("da: " + tabla.getColumn("nick").getModelIndex());
//	        		System.out.println("a ver si sale: "+ tabla.getValueAt(1, 1) );
//	        		System.out.println("Nick: " + tabla.getValueAt(tabla.getSelectedRow(), tabla.getColumn("nick").getModelIndex()));
//	        		
	        		int fila = tabla.getSelectedRow();
	        		for (int i = 0; i < txtEditables.length; i++) {
	        			txtEditables[i].setText(""+tabla.getModel().getValueAt(fila, i));
					}
	        	}
	            // do some actions here, for example
	            // print first column value from selected row
	        }
	    });
	}
}
