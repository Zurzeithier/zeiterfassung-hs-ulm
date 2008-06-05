/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package database;

import beans.TimeAccountBean;
import exceptions.DBException;

/**
 *
 * @author manuel
 */
public interface TimeAccountProxy {

    TimeAccountBean getTimeAccount() throws DBException;

}
