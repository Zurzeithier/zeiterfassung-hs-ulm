/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package database;

import beans.TPTypeBean;
import exceptions.DBException;

/**
 *
 * @author manuel, steffen
 */
public interface TPTypeProxy {

    TPTypeBean getType(int typId) throws DBException;

}
