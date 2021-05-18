//button.setFocusPainted(false);

import java.awt.Font;
import java.awt.event.FocusAdapter;
import java.awt.event.FocusEvent;
import java.awt.event.KeyAdapter;
import java.awt.event.KeyEvent;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;

import javax.swing.JPanel;
import javax.swing.JComboBox;
import javax.swing.JFormattedTextField;
import javax.swing.JTextField;
import javax.swing.SwingConstants;
import javax.swing.text.NumberFormatter;

//import java.util.Calendar;

public class Editables {
	
	private JTextField txt;
	private JFormattedTextField nmb;
	private JComboBox<String> cmb;
	private TextPrompt placeHolder;
	private JPanel contentPane;
	private String type;	//bool - int - enum - varchar - date

	// Tipos posibles: DATETIME  -  INT  -  BOOLEAN  -  VARCHAR  -  ENUM
	// int(11) - tinyint(1) - enum('ESP','ENG') - int(11)
	public Editables(String name, String type, int i, JPanel cp) {
		contentPane = cp;
		if (type.equals("tinyint(1)")) {
			cmb = new JComboBox<String>();
			cmb.setBounds(104*(i%6), (i/6)*30, 100, 20);
			cp.add(cmb);
			cmb.addItem(name);
			cmb.addItem("No "+name);
			cmb.setToolTipText(name);
			this.type = "bool";
		} else if (type.contains("int")) {
			NumberFormatter nf = new NumberFormatter();
			nmb = new JFormattedTextField(nf);
			if (name.contains("id_")) {
				nmb.setEditable(false);
			}
			nmb.setToolTipText(name);
			nmb.setValue(null);
			nmb.setBounds(104*(i%6), (i/6)*30, 100, 20);
			cp.add(nmb);
			placeHolder = new TextPrompt(name,nmb);
			placeHolder.changeAlpha(0.8f);
			placeHolder.changeStyle(Font.PLAIN);
			this.type = "int";
			nmb.addKeyListener(new KeyAdapter() {
				@Override
				public void keyPressed(KeyEvent e) {
					if (e.getKeyCode()==10) {
						if (nmb.getText().isBlank()) {
							nmb.setValue(null);
						}
						contentPane.requestFocus();
					}
				}
			});
			nmb.addFocusListener(new FocusAdapter() {
				public void focusLost(FocusEvent e) {
					if (nmb.getText().isBlank()) {
						nmb.setValue(null);
					}
				}
			});
		} else if (type.contains("enum")) {
			cmb = new JComboBox<String>();
			cmb.setBounds(104*(i%6), (i/6)*30, 100, 20);
			cp.add(cmb);
			cmb.setToolTipText(name);
			String[] str = type.split("'");
			for (int j = 1; j < str.length-1; j+=2) {
				cmb.addItem(str[j]);
			}
			this.type = "enum";
		} else if (type.contains("varchar")) {
			txt = new JTextField();
			if (name.contains("id_")) {
				txt.setEditable(false);
			}
			txt.setText("");
			txt.setToolTipText(name);
			txt.setBounds(104*(i%6), (i/6)*30, 100, 20);
			cp.add(txt);
			placeHolder = new TextPrompt(name,txt);
			placeHolder.changeAlpha(0.8f);
			placeHolder.changeStyle(Font.PLAIN);
			this.type = "varchar";
		} else if (type.contains("date")) {
			txt = new JTextField();
			txt.setEditable(false);
			txt.setText("");
			txt.setToolTipText(name);
			txt.setFont(new Font("Tahoma", Font.PLAIN, 10));
			txt.setHorizontalAlignment(SwingConstants.CENTER);
			txt.setBounds(104*(i%6), (i/6)*30, 100, 20);
			cp.add(txt);
			placeHolder = new TextPrompt(name,txt);
			placeHolder.changeAlpha(0.8f);
			placeHolder.changeStyle(Font.PLAIN);
			this.type = "date";
			
			DateTime dt = new DateTime();
			dt.setJTextField(txt);
			txt.addMouseListener(new MouseAdapter() {
				@Override
				public void mousePressed(MouseEvent e) {
					dt.setVisible(true);
				}
			});
		}else {
			System.out.println("Error de tipos");
		}
	}
	
	public void remove() {
		switch (type) {	//bool - int - enum - varchar - date
		case "bool", "enum":
			contentPane.remove(cmb);
			break;
		case "int":
			contentPane.remove(nmb);
			break;
		case "varchar":
			contentPane.remove(txt);
			break;
		case "date":
			contentPane.remove(txt);
			break;
		default:
			System.out.println("Falla el remove");
			break;
		}
	}

	public void setText(String s) {
		switch (type) {	//bool - int - enum - varchar - date
		case "bool":
			if (s == null|| s.equals("1")) {
				cmb.setSelectedIndex(0);
			} else {
				cmb.setSelectedIndex(1);
			}
			break;
		case "enum":
			if (s == null) {
				cmb.setSelectedIndex(0);
			} else {
				cmb.setSelectedItem(s);
			}
			break;
		case "int":
			if (s == null||s.isBlank()) {
				nmb.setValue(null);
			} else {
				nmb.setValue(Integer.parseInt(s));
			}
			break;
		case "varchar":
			txt.setText(s);
			break;
		case "date":
			txt.setText(s);
			break;
		default:
			System.out.println("Falla el setText");
			break;
		}
	}

	public String getText() {
		switch (type) {	//bool - int - enum - varchar - date
		case "bool":
			if (cmb.getSelectedIndex()==0) {
				return "1";
			} else {
				return "0";
			}
		case "enum":
			return cmb.getSelectedItem().toString();
		case "int":
			if (nmb.getValue() != null) {
				return nmb.getValue().toString();
			}
			return null;
		case "varchar":
			return txt.getText();
		case "date":
			if (txt.getText().equals("Fecha")||txt.getText().isBlank()) {
				return null;
			}
			return txt.getText();
		default:
			System.out.println("Falla el getText");
			break;
		}
		return "F";
	}
}
